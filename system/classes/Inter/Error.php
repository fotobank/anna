<?php

namespace classes\Inter;

/**
 * Class Error
 */
use classes\pattern\Registry;
use Exception;
use ErrorException;
use classes\File;
use proxy\Location;
use proxy\Router;

/**
 * Class Error
 * @package classes\Inter
 */
class Error
{

	/**
	 * @var array
	 */
	public $conf = [

		// страница - заглушка
		'friendlyExceptionPage' => 'stop',
		// уровень детализации лога
		'logType'               => 'detail',
		// false / simple / detail
		'logDir'                => '',
		// переменные показывамые автоматичесски
		'variables'             => ['_GET', '_POST', '_SESSION', '_COOKIE'],
		// типы игнорируемых ошибок
		'ignoreERROR'           => [],
		// временно включить лог для отладки этого скрипта ( т.к. на '127.0.0.1' лог отключен )
		'log_on'                => false
	];

	/**
	 * @var array
	 */
	private $_allError = [];

	/**
	 * @var string
	 */
	private $_request_uri;

	/**
	 * @link http://docs.php.net/manual/zh/errorfunc.constants.php
	 * @var array
	 */
	protected $_errorText = [
		'1'     => 'E_ERROR',
		'2'     => 'E_WARNING',
		'4'     => 'E_PARSE',
		'8'     => 'E_NOTICE',
		'16'    => 'E_CORE_ERROR',
		'32'    => 'E_CORE_WARNING',
		'64'    => 'E_COMPILE_ERROR',
		'128'   => 'E_COMPILE_WARNING',
		'256'   => 'E_USER_ERROR',
		'512'   => 'E_USER_WARNING',
		'1024'  => 'E_USER_NOTICE',
		'2048'  => 'E_STRICT',
		'4096'  => 'E_RECOVERABLE_ERROR',
		'8192'  => 'E_DEPRECATED',
		'16384' => 'E_USER_DEPRECATED'
	];

	private $hash_w = [];  // проверка ошибок на повторение
	private $hash_d = [];
//	private $key_fatal_error = false;
	/**
	 * @var Exception $e
	 */
	protected $e;
	/**
	 * @var int
	 * количество отображаемых линий дебага
	 */
	private $line_limit = 15;

	/**
	 * Битовая маска, определяющая какие ошибки, будут превращены в исключения
	 *
	 * @var int
	 */
	private $errorConversionMask;


	/**
	 * инициализация
	 */
	public function __construct()
		{
			date_default_timezone_set('Europe/Kiev');
			set_exception_handler([$this, 'exception_handler']);

//			$this->errorConversionMask = E_ALL ^ (E_NOTICE | E_USER_NOTICE);
			/*if (version_compare(PHP_VERSION, '5.4', '>=')) {
				$this->errorConversionMask = $this->errorConversionMask ^ E_STRICT;
			}*/
			$this->errorConversionMask = E_ALL;

			if(DEBUG_MODE) {
				error_reporting($this->errorConversionMask);
			} else {
				error_reporting(0);
			}

			set_error_handler([$this, 'error_handler']);
			if (version_compare(PHP_VERSION, '5.5', '>=')) {
				register_shutdown_function([$this, 'detect_fatal_error']);
			}
			$this->_request_uri = $this->_get_request_uri();
		}

	/**
	 *
	 */
	public function print_err()
		{
				if (DEBUG_MODE) {
					if (0 != count($this->_allError)) {
						$this->error_display();
						if ($this->conf['log_on'] ) {
							$this->write_errorlog();
						}
					}
				} else {
					if (0 != count($this->_allError)) {
						$this->write_errorlog();
					}
                    Router::stopPage();
				}
		}


	/**
	 *
	 * @param Exception $e
	 */
	public function exception_handler(Exception $e)
		{
			$errorInfo = [];
			$errorInfo['time'] = time();
			$errorInfo['type'] = 'EXCEPTION';
			$errorInfo['code'] = $e->getCode();
			if (array_key_exists($errorInfo['code'], $this->_errorText)) {
				$errorInfo['name'] = $this->_errorText[$errorInfo['code']];
			} else {
				$errorInfo['name'] = get_class($e);
			}
			$errorInfo['message'] = $e->getMessage();
			$errorInfo['file'] = $e->getFile();
			$errorInfo['line'] = $e->getLine();
			$errorInfo['trace'] = $this->_format_trace($e->getTrace());
			$errorInfo['hash'] = md5($errorInfo['code'] . $errorInfo['line'] . $errorInfo['message']);
			$this->_allError[] = $errorInfo;
			$this->e = $e;
			$this->print_err();
		}

	/**
	 * @param $errno
	 * @param $errstr
	 * @param $errfile
	 * @param $errline
	 *
	 * @return bool
	 * @throws ErrorException
	 */
	public function error_handler($errno, $errstr, $errfile, $errline)
		{
			/* Нулевое значение 'error_reporting' означает что был использован оператор "@" */
			$err_res = error_reporting();
			$mask = $errno & $this->errorConversionMask;
			if ($err_res == 0 or $mask == 0) {
				return true;
			}
			throw new ErrorException($errstr, $errno, $errno, $errfile, $errline);
		}

	/**
	 * PHP >= 5.4
	 */
	public function detect_fatal_error()
		{
			$last_error = error_get_last();
			if (0 === count($last_error)) {

				if (isset($this->_allError[0]['code']) && $this->_allError[0]['code'] == 0) {
					$this->print_err();
				}
				return false;
			}
			if (($last_error && ($last_error['type'] == E_ERROR || $last_error['type'] == E_PARSE ||
								 $last_error['type'] == E_COMPILE_ERROR) && // если кончилась память
				 0 == strpos($last_error['message'], 'Allowed memory size'))
			) {
				// выделяем немножко, что бы доработать корректно
				ini_set('memory_limit', ((int) (ini_get('memory_limit')) + 64) . 'M');
			}

			if (empty($this->conf['ignoreERROR']) || !in_array($last_error['type'], $this->conf['ignoreERROR'], true)) {
				$errorInfo = [];
				$errorInfo['time'] = time();
				$errorInfo['type'] = 'ERROR_GET_LAST';

				if (!empty($this->_errorText[$last_error['type']])) {
					$errorInfo['name'] = $this->_errorText[$last_error['type']];
				} else {
					$errorInfo['name'] = '_UNKNOWN_';
				}
				$errorInfo['code'] = $last_error['type'];
				$errorInfo['message'] = $last_error['message'];
				$errorInfo['file'] = $last_error['file'];
				$errorInfo['line'] = $last_error['line'];
				//   $errorInfo['trace'] = array();
				$trace = debug_backtrace();
				//	unset($trace[0]);
				$errorInfo['trace'] = $this->_format_trace($trace);
				$errorInfo['hash'] = md5($errorInfo['code'] . $errorInfo['line'] . $errorInfo['message']);
				$this->_allError[] = $errorInfo;
			}
			$this->print_err();

			return true;
		}


	/**
	 * request_uri
	 * @return string
	 */
	protected function _get_request_uri()
		{
			if (array_key_exists('PHP_SELF', $_SERVER)) {
				if (!empty($_SERVER['argv']) && array_key_exists(0, $_SERVER['argv'])) {
					return $_SERVER['PHP_SELF'] . '?' . $_SERVER['argv'][0];
				} elseif (array_key_exists('QUERY_STRING', $_SERVER)) {
					return $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
				} else {
					return $_SERVER['PHP_SELF'];
				}
			}
			if (array_key_exists('REQUEST_URI', $_SERVER)) {
				return $_SERVER['REQUEST_URI'];
			}

			return '_UNKNOWN_URI_';
		}


	/**
	 *
	 * @param array $trace
	 *
	 * @return array $trace
	 */
	private function _format_trace($trace)
		{
			$return = [];
			foreach ($trace as $stack => $detail) {
				$args_string = (!empty($detail['args'])) ? $this->_args_to_string($detail['args']) : $args_string = '';

				$return[$stack]['class'] = !empty($trace[$stack]['class']) ? $trace[$stack]['class'] : '';
				$return[$stack]['type'] = !empty($trace[$stack]['type']) ? $trace[$stack]['type'] : '';
				$return[$stack]['function'] =
					!empty($trace[$stack]['function']) ? $trace[$stack]['function'] . '(' . $args_string . ')' : '';
				$return[$stack]['file'] = !empty($trace[$stack]['file']) ? $trace[$stack]['file'] : '';
				$return[$stack]['line'] = !empty($trace[$stack]['line']) ? $trace[$stack]['line'] : '';
			}

			return $return;
		}


	/**
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	private function _args_to_string($args)
		{
			//    $string = '';
			$argsAll = [];
			foreach ($args as $key => $value) {
				if (true == is_object($value)) {
					$argsAll[$key] = 'Object(' . get_class($value) . ')';
				} elseif (true == is_numeric($value)) {
					$argsAll[$key] = $value;
				} elseif (true == is_string($value)) {
					$temp = $value;
					if (!extension_loaded('mbstring')) {
						if (strlen($temp) > 300) {
							$temp = substr($temp, 0, 300) . '...';
						}
					} else {
						if (mb_strlen($temp) > 300) {
							$temp = mb_substr($temp, 0, 300) . '...';
						}
					}
					$argsAll[$key] = "'{$temp}'";
					$temp = null;
				} elseif (true == is_bool($value)) {
					if (true == $value) {
						$argsAll[$key] = 'true';
					} else {
						$argsAll[$key] = 'false';
					}
				} else {
					$argsAll[$key] = gettype($value);
				}
			}
			$string = implode(',', $argsAll);

			return $string;
		}



	public function write_errorlog()
		{
			if ((false != (bool) $this->conf['logType'])) {

				foreach ($this->_allError as $errorInfo) {
					if (!in_array($errorInfo['hash'], $this->hash_w, true)) {
						$this->hash_w[] = $errorInfo['hash'];
						$logText = '';
						$logText .= date('d-m-Y H:i:s', $errorInfo['time']) . "\t" . $this->_request_uri . "\t" .
									$errorInfo['type'] . "\t" . $errorInfo['name'] . "\t" . 'Code: ' .
									$errorInfo['code'] . "\t" . $errorInfo['message'] . "\t" . $errorInfo['file'] .
									"\t" . 'Line ' . $errorInfo['line'];

						if ('detail' == $this->conf['logType'] && !empty($errorInfo['trace'])) {
							$prefix = "\n[TRACE]\t#";
							foreach ($errorInfo['trace'] as $stack => $trace) {
								$logText .= $prefix . $stack . "\t" . $trace['file'] . "\t" . $trace['line'] . "\t" .
											$trace['class'] . $trace['type'] . $trace['function'];
							}
						}
					} else {
						continue;
					}

					$logFilename = $this->conf['logDir'] . DIRECTORY_SEPARATOR . date('d-m-Y', time());
					$logFilename .=	'_' . $errorInfo['name'] . '_';
					$logFilename .= md5($errorInfo['code'] . $errorInfo['line'] . $errorInfo['message']) . '.log';

					try {

						$log = Registry::call('classes\File\Log');

						$log->put_log($logFilename, $logText);

						if (!$log->isExists()) {
							error_log($logText);
						}
						unset($logText);
					}
					catch (Exception $e) {
						throw new Exception ('Ошибка записи лога: ' . $e->getMessage(), E_USER_ERROR);
					}

				}
			}
		}


	/**
	 *
	 * @return bool
	 */
	public function error_display()
		{
			if (false !== DEBUG_MODE) {
				$htmlText = '';
				$message = '';
				foreach ($this->_allError as $key => $errorInfo) {
					if (!in_array($errorInfo['hash'], $this->hash_d, true)) {
						$this->hash_d[] = $errorInfo['hash'];
						$htmlText .=
							'<div class="intererrorblock"><div class="intererrortitle"><strong>[' . $errorInfo['name'] .
							'][Code: ' . $errorInfo['code'] . ']</strong>  ' .
							'<div class="intererrormessage">'.$errorInfo['message'] . '</div></div>' .
							'<div class="intererrorsubtitle">Line ' . $errorInfo['line'] . ' On <a href="' .
							$errorInfo['file'] . '">' . $errorInfo['file'] .
							'</a></div><div class="intererrorcontent">';

						if (empty($errorInfo['trace'])) {
							$htmlText .= 'No Traceable Information.';
						} else {
							$htmlText .= '<table width="100%" border="1" cellpadding="1" cellspacing="1" rules="rows">
									<tr>
										<th scope="col">#</th>
										<th scope="col" style="width: 300px;">File</th>
										<th scope="col" style="width: 35px;">Line</th>
										<th scope="col">Class::Method(Args)</th>
									</tr>';
							foreach ($errorInfo['trace'] as $stack => $trace) {
								$htmlText .= '<tr>
										<td>' . $stack . '</td>
										<td><a href="' . $trace['file'] . '">' . $trace['file'] . '</a></td>
										<td>' . $trace['line'] . '</td>
										<td>' . $trace['class'] . $trace['type'] .
											 htmlspecialchars($trace['function']) . '</td>
									</tr>';
							}
							$htmlText .= '</table>';
						}

						$htmlText .= '</div></div>';

						$message = $this->getUserErrNotification();
					} else {
						continue;
					}
				}
				header( 'Content-type: text/html; charset=windows-1251' );
				echo <<<END
<HTML>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
    <meta http-equiv="content-language" content="ru" />
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
  <link rel="icon" href="/images/favicon.png"  type="image/png" />
  <link rel="shortcut icon" href="/images/favicon.png" />
<style type="text/css">
<!--
        .code,
        .trace
        {
          //  overflow-x: scroll !important;
            padding: .5em !important;
        }

        .code code
        {
            display: block !important;
        }

        .code .error-line
        {
            background-color: #faa !important;
        }

        .trace
        {
            padding: .5em 1em !important;
        }

.intererrorblock table {
color: #000;
}
.intererrorblock {
    border: solid 1px #888 !important;
	font-size: 1em;
	background-color: #F0F0F0;
	text-align: left;
	vertical-align: middle;
	display: inline-block;
	border-collapse: collapse;
	word-break: break-all;
	z-index: 10000;
	overflow: visible;
	width: 1000px;
	position: relative;
}

DIV.container {
    z-index: 10000;
    width: 1000px;
    height: auto;
    position: relative;
    font-size: 1em;
    color: #000 !important;
    margin: 5px auto;
    overflow: visible;
    display: none;
}

.intererrorblock a:link {
	color: #00F;
	text-decoration: none;
}
.intererrorblock a:visited {
	text-decoration: none;
	color: #00F;
}
.intererrorblock a:hover {
	text-decoration: underline;
	color: #00F;
}
.intererrorblock a:active {
	text-decoration: none;
	color: #00F;
}

.intererrortitle {
	color: #FFF;
	background-color: #963;
	padding: 3px;
}

.intererrormessage {
    font-size: 16px;

}

.intererrorTime {
	color: #FFF;
	background-color: #8D6292;
	padding: 2px 2px 2px 5px;
	text-aglin: center;
}

.intererrorsubtitle {
	padding: 3px;
	font-weight: bold;
	color: #F00;
}

.intererrorcontent {
	font-size: 11pt;
	color: #000;
	background-color: #FFF;
	padding: 3px;
}

.intererrorcontent table{
	font-size:14px;
	word-break: break-all;
	background-color:#D4D0C8;
	border-color:#000000;
}

.intererrorblock table a:link {
	color: #00F;
	text-decoration: none;
}
.intererrorblock table a:visited {
	text-decoration: none;
	color: #00F;
}
.intererrorblock table a:hover {
	text-decoration: underline;
	color: #00F;
}
.intererrorblock table a:active {
	text-decoration: none;
	color: #00F;
}

-->
</style>
</head>
<body>
<div class="container">
{$htmlText}
{$message}
</div>

<script type="text/javascript">
window.onload = function () {

	var texno = document.getElementsByClassName('texno');
	var container = document.getElementsByClassName('container');

	if(texno.length) {
	var wrap = "<div class='intererrorblock'><div class='intererrorTime'>" + texno[0].outerHTML + "</div></div>";
	texno[0].outerHTML = null;
    container[0].innerHTML += wrap;
	}

    container[0].style.display = 'block'; // показать блок ошибок

  return false;
}
</script>
</body>
</html>
END;

				//	включить показ системных переменных
				//	$this->show_variables($this->conf['variables']);
			}

			return true;
		}

	/**
	 *
	 */
	protected function addElements()
		{
			$num = func_num_args();
			$args = func_get_args();
			for ($i = 0; $i < $num; $i ++) {
				$masiv[] = $args[$i];
			}
		}

	public function var_dump()
		{
			$oldVar = $this->conf['variables'];
			$this->conf['variables'] = func_get_args();
			$this->show_variables($this->conf['variables']);
			$this->conf['variables'] = $oldVar;
		}

	/**
	 * Возвращает строку ошибки
	 *
	 *
	 * @return bool|string
	 *
	 * @since 1
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameters)
	 */
	private function getUserErrNotification()
		{
			if ($this->e) {
				$err_line = $this->e->getLine();
				$lines = file($this->e->getFile());
				$isNormalMode = current(current($this->e->getTrace())) != 'fatalErrorHandler';
			} else {
				$err_line = $this->_allError[0]['line'];
				$lines = file($this->_allError[0]['file']);
				$isNormalMode = true;
			}

				$firstLine = $err_line < $this->line_limit ? 0 : $err_line - ceil($this->line_limit * 0.7);
				$lastLine = $firstLine + $this->line_limit < count((array)$lines) ?
					$firstLine + $this->line_limit : count((array)$lines);
				$code = '';


				for ($i = $firstLine - 1; $i < $lastLine; $i ++) {
					$s = ($i + 1) . '  ' . $lines[$i];
					if ($isNormalMode) {
						$s = highlight_string('<?php' . $s, true);
						$s = preg_replace('/&lt;\?php/', '', $s, 1);
					} else {
						$s = '<pre>' . htmlspecialchars($s) . '</pre>';
					}

					if ($i == $err_line - 1) {
						$s = preg_replace('/(<\w+)/', '$1 class="error-line"', $s);
					}
					$code .= $s;
				}

				return '
    <article>
        <section>
        <div class="intererrorblock">
            <div class="intererrortitle"><b>[Trace]</b></div>
            <div class="code">
            ' . $code . '
            </div></div>
        </section>
    </article>';

		}

	/**
	 * @param $variables
	 */
	public function show_variables($variables)
		{
			$variables_link = '';
			$variables_content = '';
			foreach ($variables as $key) {

				$variables_link .= '<a href="#variables' . $key . '" style="color: #A0A">$' . $key .
								   '<strong style="color: #000">;</strong></a>&nbsp;';
				$variables_content .=
					'<div class="variablessubtitle"><a name="variables' . $key . '" id="variables' . $key . '"></a>
                                       <strong style="color: #0000aa;">$' . $key . ':</strong></div>
						               <div class="variablescontent">';

				if (!array_key_exists($key, $GLOBALS)) {
					$variables_content .= '$' . $key . ' IS NOT SET.';
				} else {
					$variables_content .= '<pre>' . htmlspecialchars(print_r($GLOBALS[$key], 1)) . '<pre>';
				}
				$variables_content .= '</div>';
			}

			header( 'Content-type: text/html; charset=windows-1251' );
			echo <<<END
<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
    <meta http-equiv="content-language" content="ru" />
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
<link rel="icon" href="/images/favicon.png"  type="image/png" />
<link rel="shortcut icon" href="/images/favicon.png" />
<style type="text/css">
<!--
.variablesblock {
	font-size: 12pt;
	background-color: #CCC;
	text-align: left;
	vertical-align: middle;
	display: inline-block;
	border-collapse: collapse;
	word-break: break-all;
	padding: 3px;
	width: 1000px;
	color: #000;
position: relative;
}

DIV.container {
    width: 100%;
    position: relative;
    color: #000 !important;
    font-family: monospace !important;
    font-size: 1em !important;
    margin: 0 auto;
    overflow: visible;
}

.variablesblock a:link {
	color: #000;
	text-decoration: none;
}
.variablesblock a:visited {
	text-decoration: none;
	color: #000;
}
.variablesblock a:hover {
	text-decoration: underline;
	color: #000;
}
.variablesblock a:active {
	text-decoration: none;
	color: #000;
}

.intererrorTime {
	color: #FFF;
	background-color: #8D6292;
	padding: 2px 2px 2px 5px;
	text-aglin: center;
}

.variablessubtitle {
	padding: 3px;
	font-weight: bold;
	border: 1px solid #FFF;
}

.variablescontent {
	font-size: 11pt;
	color: #000;
	background-color: #EEE;
	padding: 3px;
}
-->
</style>
</head>
<body>
<div class="container">
<div class="variablesblock">
    <div class="variablessubtitle">Variables: {$variables_link}</div>
    {$variables_link}
</div></div>

<script type="text/javascript">
window.onload = function () {

	var texno = document.getElementsByClassName('texno');
	var container = document.getElementsByClassName('container');

	if(texno.length) {
	var wrap = "<div class='intererrorblock'><div class='intererrorTime'>" + texno[0].outerHTML + "</div></div>";
	texno[0].outerHTML = null;
    container[0].innerHTML += wrap;
	}

    container[0].style.display = 'block'; // показать блок ошибок

  return false;
}
</script>
</body>
</html>
END;

		}
}