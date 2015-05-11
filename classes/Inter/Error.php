<?php

/**
 * Class Error
 */
class Error
{

    /**
	 * @var array
	 */
    public static $conf = [
                                'debugMode' => false,
                                'friendlyExceptionPage' => 'stop.php',
                                'logType' => 'detail', // false / simple / detail
                                'logDir' => '',
                                'suffix' => '-Inter-ErrorLog.log',
                                'variables' => [ "_GET", "_POST", "_SESSION", "_COOKIE" ],
                                'ignoreERROR' => [ ],
								 'email' => 'aleksjurii@gmail.com', //  email для отправки ошибок
								 'time_log' => 5, // время в минутах между обновлениями лога
								 'otl' => false,  // временно включить лог для отладки этого скрипта ( т.к. на '127.0.0.1' лог отключен )
								 'max_dir' => 1000 // максимальный размер лог папки в килобайтах, после которого папка самоочистится
	];

    /**
	 * @var array
	 */
    private static $_allError = [ ];

    /**
	 *
	 */
    private static $_registered = false;
    
    /**
     * @var string
     */
    private static $_request_uri = null;
    
    /**
     * @link http://docs.php.net/manual/zh/errorfunc.constants.php
     * @var array
     */
    private static $_errorText = [
    						'1'=>'E_ERROR',
                            '2'=>'E_WARNING',
                            '4'=>'E_PARSE',
                            '8'=>'E_NOTICE',
                            '16'=>'E_CORE_ERROR',
                            '32'=>'E_CORE_WARNING',
                            '64'=>'E_COMPILE_ERROR',
                            '128'=>'E_COMPILE_WARNING',
                            '256'=>'E_USER_ERROR',
                            '512'=>'E_USER_WARNING',
                            '1024'=>'E_USER_NOTICE',
                            '2048'=>'E_STRICT',
                            '4096'=>'E_RECOVERABLE_ERROR',
                            '8192'=>'E_DEPRECATED',
                            '16384'=>'E_USER_DEPRECATED',
	];

	private static $hash_w =  [ ];  // проверка ошибок на повторение
	private static $hash_d =  [ ];
	private static $key_fatal_error = false;


	/**
	 * @return bool
	 */
	public static function init(){
		if( self::$_registered  == false){
			date_default_timezone_set ("Europe/Moscow");
			set_exception_handler( [ 'Error', 'exception_handler' ] );
			set_error_handler( [ 'Error', 'error_handler' ], E_ALL);
			self::$_registered = new Error();
			self::$conf['debugMode'] = DEBUG_MODE;
			if(version_compare(PHP_VERSION, '5.2', '>=')){
				register_shutdown_function( [ 'Error', 'detect_fatal_error' ] );
			}
			self::$_request_uri = self::_get_request_uri();
			self::$_registered = true;
		}
		return self::$_registered;
	}

	public static function print_err(){
		if(self::$key_fatal_error) {
		if(DEBUG_MODE) {
		    self::error_display();
			if(self::$conf['otl']) {self::write_errorlog();}
		} else {
		    self::write_errorlog();
		}
		}
	}

	/**
     *
     * @param Exception $e
     */
    public static function exception_handler(Exception $e){

        $errorInfo = [ ];
        $errorInfo['time'] = time();
        $errorInfo['type'] = 'EXCEPTION';
        $errorInfo['name'] = get_class($e);
        $errorInfo['code'] = $e->getCode();
        $errorInfo['message'] = $e->getMessage();
        $errorInfo['file'] = $e->getFile();
        $errorInfo['line'] = $e->getLine();
        $errorInfo['trace'] = self::_format_trace($e->getTrace());
		$errorInfo['hash'] = md5($errorInfo['code']. $errorInfo['line']. $errorInfo['message']);
        self::$_allError[] = $errorInfo;
		self::print_err();
    }

	public static function printExceptionPage(){
		if(self::$conf['debugMode'] == false) {
			if(is_file(SITE_PATH . self::$conf['friendlyExceptionPage'])){
				require(SITE_PATH . self::$conf['friendlyExceptionPage']);
			}
		} else {
			self::print_err();
		}
	}

    /**
     *
     * @param integer $errno
     * @param string $errstr
     * @param string $errfile
     * @param string $errline
     */
	/**
	 * @param $errno
	 * @param $errstr
	 * @param $errfile
	 * @param $errline
	 *
	 * @return bool
	 */
	public static function error_handler($errno, $errstr, $errfile, $errline) {
        
        if( empty(self::$conf['ignoreERROR']) || !in_array($errno, self::$conf['ignoreERROR']) ){
            $errorInfo = [ ];
            $errorInfo['time'] = time();
            $errorInfo['type'] = 'ERROR';
            
            if(!empty(self::$_errorText[$errno])){
                $errorInfo['name'] = self::$_errorText[$errno];
            }else{
                $errorInfo['name'] = '_UNKNOWN_';
            }
            $errorInfo['code'] = $errno;
            $errorInfo['message'] = $errstr;
            $errorInfo['file'] = $errfile;
            $errorInfo['line'] = $errline;
            $trace = debug_backtrace();
            unset($trace[0]);
            $errorInfo['trace'] = self::_format_trace($trace);
				$errorInfo['hash'] = md5($errno.$errline.$errstr);
				self::$_allError[] = $errorInfo;
			}

		if( in_array($errno, [ 1, 4, 16, 64, 4096 ] ) ){
			self::print_err();
				die();
		}
		self::print_err();
    return true;
 }
    
    /**
     * PHP >= 5.2
     */
    public static function detect_fatal_error(){
		self::$key_fatal_error = true;
        $last_error = error_get_last();
        if(empty($last_error)){
			if(isset(self::$_allError[0]['code']) && self::$_allError[0]['code'] == 0){
				self:: printExceptionPage();
			}
			self::print_err();
            return false;
        }

		if ($last_error && ($last_error['type'] == E_ERROR || $last_error['type'] == E_PARSE || $last_error['type'] == E_COMPILE_ERROR)) {
			if (strpos($last_error['message'], 'Allowed memory size') === 0) { // если кончилась память
				ini_set('memory_limit', (intval(ini_get('memory_limit'))+64)."M"); // выделяем немножко, что бы доработать корректно
				self::write_errorlog("PHP Fatal: not enough memory in ".$last_error['file'].":".$last_error['line']);
			}
		}

        if(!empty(self::$_allError)){
            $log_last_error = end(self::$_allError);
            //reset(self::$_allError);
            if($log_last_error['code'] == $last_error['type'] && $log_last_error['file'] == $last_error['file'] && $log_last_error['line'] == $last_error['line']){
                return false;
            }
        }

        if( empty(self::$conf['ignoreERROR']) || !in_array($last_error['type'], self::$conf['ignoreERROR']) ){
            $errorInfo = [ ];
            $errorInfo['time'] = time();
            $errorInfo['type'] = 'ERROR_GET_LAST';
            
            if(!empty(self::$_errorText[$last_error['type']])){
                $errorInfo['name'] = self::$_errorText[$last_error['type']];
            }else{
                $errorInfo['name'] = '_UNKNOWN_';
            }
            
            $errorInfo['code'] = $last_error['type'];
            $errorInfo['message'] = $last_error['message'];
            $errorInfo['file'] = $last_error['file'];
            $errorInfo['line'] = $last_error['line'];
         //   $errorInfo['trace'] = array();
			$trace = debug_backtrace();
		//	unset($trace[0]);
			$errorInfo['trace'] = self::_format_trace($trace);
			$errorInfo['hash'] = md5($errorInfo['code'].$errorInfo['line'].$errorInfo['message']);
            self::$_allError[] = $errorInfo;
			if( in_array($errorInfo['code'], [ 1, 4, 16, 64, 4096 ] ) ){

				self:: printExceptionPage();
			    return false;
			}
        //    echo 'ERROR_GET_LAST INFO: '. var_export($errorInfo);
        }
		self::print_err();
	}

    /**
     * request_uri
     * @return string
     */
    protected static function _get_request_uri(){

        if(isset($_SERVER['PHP_SELF'])){
	        if(isset($_SERVER['argv'][0])){
                return $_SERVER['PHP_SELF']. '?'. $_SERVER['argv'][0];
	        }elseif(isset($_SERVER['QUERY_STRING'])){
		        return $_SERVER['PHP_SELF']. '?'. $_SERVER['QUERY_STRING'];
	        }else{
		        return $_SERVER['PHP_SELF'];
	        }
		}
			if(isset($_SERVER['REQUEST_URI'])){
				return $_SERVER['REQUEST_URI'];
			}
	        return '_UNKNOWN_URI_';
    }


    /**
     *
     * @param array $trace
     * @return array $trace
     */
    private static function _format_trace($trace){
        $return = [ ];
        foreach ($trace as $stack => $detail){
            if(!empty($detail['args'])){
                $args_string = self::_args_to_string($detail['args']);
            }else{
                $args_string = '';
            }
            $return[$stack]['class'] = isset($trace[$stack]['class']) ? $trace[$stack]['class'] : '';
            $return[$stack]['type'] = isset($trace[$stack]['type']) ? $trace[$stack]['type'] : '';
            $return[$stack]['function'] = isset($trace[$stack]['function']) ? $trace[$stack]['function'].'('.$args_string.')' : '';
            $return[$stack]['file']=isset($trace[$stack]['file']) ? $trace[$stack]['file'] :'' ;
            $return[$stack]['line']=isset($trace[$stack]['line']) ? $trace[$stack]['line'] :'' ;
        }
        return $return;
    }


    /**
     *
     * @param array $args
     * @return string
     */
    private static function _args_to_string($args){
    //    $string = '';
        $argsAll = [ ];
        foreach ($args as $key => $value){
            if(true == is_object($value)){
                $argsAll[$key] = 'Object('.get_class($value).')';
            }elseif(true == is_numeric($value)){
                $argsAll[$key] = $value;
            }elseif(true == is_string($value)){
                $temp = $value;
                if(!extension_loaded('mbstring')){
                    if(strlen($temp) > 300){
                        $temp = substr($temp, 0 ,300).'...';
                    }
                }else{
                    if(mb_strlen($temp) > 300){
                        $temp = mb_substr($temp, 0 ,300).'...';
                    }
                }
                $argsAll[$key] = "'{$temp}'";
                $temp = null;
            }elseif(true == is_bool($value)){
                if(true == $value){
                    $argsAll[$key] = 'true';
                }else{
                    $argsAll[$key] = 'false';
                }
            }else{
                $argsAll[$key] = gettype($value);
            }
        }
        $string = implode(',', $argsAll);
        return $string;
    }


	/**
     *
     */
    public static function write_errorlog(){
        if( (false != (bool)self::$conf['logType']) && !empty(self::$_allError)){
			try {
				$log = new log();
				$log->setTime_log(5)
					->setMax_dir(10000)
					->setEmail('aleksjurii@gmail.com');
			} catch (Exception $e) {
				trigger_error("Ошибка присвоения данных: " . $e->getMessage(), E_USER_ERROR );
			}
            foreach (self::$_allError as $errorInfo) {
				if( !in_array($errorInfo['hash'], self::$hash_w) ) {
					self::$hash_w[] = $errorInfo['hash'];
				$logText = '';
                $logText .= date("d-m-Y H:i:s", $errorInfo['time']). "\t".
                self::$_request_uri."\t".
                $errorInfo['type']. "\t".
                $errorInfo['name']. "\t".
                'Code '. $errorInfo['code']. "\t".
                $errorInfo['message']. "\t".
                $errorInfo['file']. "\t".
                'Line '. $errorInfo['line'];

                if('detail' == self::$conf['logType'] && !empty($errorInfo['trace'])) {
                    $prefix = "\n[TRACE]\t#";
                    foreach ( $errorInfo['trace'] as $stack => $trace ) {
                        $logText .= $prefix. $stack. "\t". $trace['file']. "\t". $trace['line']. "\t". $trace['class']. $trace['type']. $trace['function'];
                    }
                }
			  } else { continue ;}

					$logFilename = self::$conf['logDir'] . DIRECTORY_SEPARATOR . date("d-m-Y", time()).'_'.$errorInfo['name'].'_';
				    $logFilename .= md5($errorInfo['code'].$errorInfo['line'].$errorInfo['message']). '.log';
				    $log->put_log($logFilename, $logText);
				    $logText = '';
				    if(!$log->exists()) error_log($logText);

            }
        }
    }


	/**
	 * @return bool
	 */
	public static function error_display(){
        if(false != self::$conf['debugMode'] && !empty(self::$_allError)){
            $htmlText = '';
            foreach (self::$_allError as $key => $errorInfo){
				if( !in_array($errorInfo['hash'], self::$hash_d) ){
					self::$hash_d[] = $errorInfo['hash'];
                $htmlText .= '<div class="intererrorblock">
    							<div class="intererrortitle">['.$errorInfo['name'].'][Code '.$errorInfo['code'].'] '.$errorInfo['message'].'</div>
    							<div class="intererrorsubtitle">Line '.$errorInfo['line'].' On <a href="'.$errorInfo['file'].'">'.$errorInfo['file'].'</a></div>
    							<div class="intererrorcontent">
							';

                if(empty($errorInfo['trace'])){
                    $htmlText .= 'No Traceable Information.';
                }else{
                    $htmlText .= '<table width="100%" border="1" cellpadding="1" cellspacing="1" rules="rows">
									<tr>
										<th scope="col">#</th>
										<th scope="col" style="width: 300px;">File</th>
										<th scope="col" style="width: 35px;">Line</th>
										<th scope="col">Class::Method(Args)</th>
									</tr>';
                    foreach ($errorInfo['trace'] as $stack => $trace){
                        $htmlText .= '<tr>
										<td>'.$stack.'</td>
										<td><a href="'.$trace['file'].'">'.$trace['file'].'</a></td>
										<td>'.$trace['line'].'</td>
										<td>'.$trace['class']. $trace['type']. htmlspecialchars($trace['function']) .'</td>
									</tr>';
                    }
                    $htmlText .= '</table>';
                }

                $htmlText .= '	</div>
    						</div>
							';
            } else { continue; }
		}
            echo <<<END
<style type="text/css">
<!--
.intererrorblock table {
color: #000;
}
.intererrorblock {
	font-size: 12pt;
	background-color: #FFC;
	text-align: left;
	vertical-align: middle;
	display: inline-block;
	border-collapse: collapse;
	word-break: break-all;
	padding: 3px;
	z-index: 10000;
	overflow: visible;
	margin-bottom:3px;
	width: 1000px;
	position: relative;
}

DIV.container {
    z-index: 10000;
    width: 1000px;
    height: auto;
    position: relative;
    font-size: .8em;
    text-align: center;
    margin: 5px auto;
    overflow: visible;
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
	font-weight: bold;
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
<div class="container">
{$htmlText}
</div>
END;

       self::show_variables(self::$conf['variables']);
        }
		return true;
    }

	function addElements()
	{
		$num = func_num_args();
		$args = func_get_args();
		for($i=0; $i<$num; $i++)
		{
			array_push($masiv,$args[$i]);
		}
	}

	public static function var_dump(){

		$oldVar = self::$conf['variables'];
		self::$conf['variables'] = func_get_args();
		self::show_variables(self::$conf['variables']);
		self::$conf['variables'] = $oldVar;

	}

	/**
	 * @param $variables
	 */
	public static function show_variables($variables){
		$variables_link = '';
		$variables_content = '';
		foreach( $variables as $key ){

				$variables_link .= '<a href="#variables'.$key.'" style="color: #A0A">$'.$key.'<strong style="color: #000">;</strong></a>&nbsp;';
				$variables_content .= '<div class="variablessubtitle"><a name="variables'.$key.'" id="variables'.$key.'"></a>
                                       <strong style="color: #00A">$'.$key.':</strong></div>
						               <div class="variablescontent">';

				if(!isset($GLOBALS[$key])){
					$variables_content .= '$'. $key .' IS NOT SET.';
				}else{
					$variables_content .= "<pre>".htmlspecialchars(print_r($GLOBALS[$key],1))."<pre>";
				}
				$variables_content .= '</div>';
		}


		echo <<<END
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
    font-size: .8em;
    text-align: center;
    margin: 5px auto;
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
<div class="container">
<div class="variablesblock">
    <div class="variablessubtitle">Variables: {$variables_link}</div>
    {$variables_content}
</div></div>
END;

	}
}