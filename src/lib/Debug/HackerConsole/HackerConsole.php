<?php
/**
 * http://forum.dklab.ru/users/DmitryKoterov/
 * @version 1.x $Id: HackerConsole.php 168 2007-01-30 21:12:03Z dk $
 */

namespace lib\Debug\HackerConsole;


require_once(__DIR__ . '/../config.php');


/**
 * Class HackerConsole
 * ������������ �� Ctr/~
 *
 * @package lib\Debug\HackerConsole
 */
class HackerConsole
{

    public $hc_height = '300'; // height of the console (pixels)
    private static $hc_entries = [];
    public $TAB_SIZE = 4;
    private static $instance;
    private static $display = true;


    /**
     * @param bool $autoAttach
     *
     * @return HackerConsole|null
     */
    protected static function getInstance($autoAttach = false)
    {
        if(self::$instance == null)
        {
            self::$instance = new HackerConsole($autoAttach);
        }

        return self::$instance;
    }


    /**
     * constructor($autoAttachToHtmlOutput=true)
     * Create new console. If $autoAttachToHtmlOutput, output buffering
     * handler is set to automatically attach JavaScript showing code to
     * HTML page.
     *
     * @param bool $autoAttach
     */
    public function __construct($autoAttach = false)
    {
        if($autoAttach)
        {
            ob_start([&$this, 'obHandler']);
        }
        $GLOBALS['Debug_HackerConsole_Main_LAST'] = &$this;
    }


    /**
     * string attachToHtml(string $pageHtml)
     * Attach the console to given HTML page.
     *
     * @param $page
     *
     * @return mixed
     */
    public function attachToHtml($page)
    {
        if(!self::$display)
        {
            return $page;
        }
        $js = implode('', file(__DIR__ . '/Js.js'));
        if(get_magic_quotes_runtime())
        {
            $js = stripslashes($js);
        }
        $js = str_replace('{HEIGHT}', $this->hc_height, $js);
        // We MUST use "hackerConsole" instead of "console" because of Safari.
        $code =
            "window.hackerConsole = window.hackerConsole || window.Debug_HackerConsole_Js && new window.Debug_HackerConsole_Js();\n";
        $code .= "if (window.hackerConsole) setTimeout(function() { with (window.hackerConsole) {\n";
        foreach(self::$hc_entries as $gid => $elements)
        {
            foreach($elements as $e)
            {
                if($e['tip'] === null)
                {
                    $file = str_replace('\\', '/', $e['file']);
                    if(isset($_SERVER['DOCUMENT_ROOT']))
                    {
                        // Under IIS DOCUMENT_ROOT may not be available.
                        $dr   = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
                        $file = preg_replace('{^' . preg_quote($dr, '{}') . '}is', '~', $file);
                    }
                    $title = "at {$file} line {$e['line']}" . (!empty($e['function']) ? ", {$e['function']}" : '');
                }
                else
                {
                    $title = $e['tip'];
                }
                $text = $this->toPre($e['text']);
                if(!empty($e['color']))
                {
                    $text = "<div style=\"color:{$e['color']}\">$text</div>";
                }
                $code .= '  out(' . $this->_toJs($text) . ', ' . $this->_toJs($title) . ', ' . $this->_toJs($gid)
                    . ");\n";
            }
        }
        $code .= '}}, 200);';
        $html = '';
        // Dirty close opened tags. This is bad, but better than nothing...
        $lower = strtolower($page);
        if(strpos($lower, '</body>') === false)
        {
            foreach(['script', 'xmp', 'pre'] as $tag)
            {
                if(substr_count($lower, "<$tag") > substr_count($lower, "</$tag"))
                {
                    $html .= "</$tag>";
                }
            }
        }
        $html .= "\n";
        if(!$this->is_ajax())
        {
            $html .= "<script type=\"text/javascript\" language=\"JavaScript\">//<![CDATA[\n{$js}\n{$code}\n//]]></script>\n";
        }
        $page = preg_replace('{(?=</body[^>]*>|$)}si', preg_replace('/([\\\\$])/', '\\\\$1', $html), $page, 1);

        return $page;
    }

    /**
     *  ��� ajax ������� ��� ������ true, � ��� �������� ������� false.
     *
     * Check if current request is AJAX.
     */
    private function is_ajax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /**
     * void out(string $msg, string $group="message", $color=null, $tip=null)
     * Add new message to the console.
     * Messages may be grouped together using $group parameters for better view.
     * By default messages are tipped with caller context (file, line).
     * Contexts generated by call_user_func() are skipped!
     *
     * @param        $v
     * @param string $group
     * @param null   $color
     * @param null   $tip
     */
    public static function out($v, $group = 'message', $color = null, $tip = null)
    {
        $debug = HackerConsole::getInstance(true);
        // Have to work only with $obj, NOT $this!
        if(empty($debug) || basename(self::class) != 'HackerConsole')
        {
            $obj = &$GLOBALS['Debug_HackerConsole_Main_LAST'];
        }
        else
        {
            $obj = &$debug;
        }

        if(!$obj)
        {
            return;
        }

        // Detect caller if needed. Used in tip.
        $s = [];
        if($tip === null)
        {
            // Find caller. Use call_user_func to get context of out() calling.
            $s = call_user_func(
                [&$obj, 'debug_backtrace_smart'],
                'call_user_func.*', // ignore indirect contexts
                true
            );
        }

        if(is_scalar($v))
        {
            $text = "$v\n";
        }
        else
        {
            $text = self::print_r($v, true);
        }

        self::$hc_entries[$group][] = [
            'file'     => isset($s['file']) ? $s['file'] : null,
            'line'     => isset($s['line']) ? $s['line'] : null,
            'function' => isset($s['function']) ? $s['function'] : null,
            'text'     => $text,
            'color'    => $color,
            'tip'      => $tip,
        ];
    }


    /**
     * void display()
     * Disable displaying of the console.
     */
    public function displayOn()
    {
        self::$display = true;
    }

    /**
     * void display()
     * Disable displaying of the console.
     */
    public function displayOff()
    {
        self::$display = false;
    }

    /**
     * string toPre($text)
     * Format plaintext like <pre> tag does, but with <br> at the line tails
     * and &nbsp; in line prefixes.
     */
    public function toPre($text, $tabSize = null)
    {
        $text = htmlspecialchars($text);
        // Expand tabulators.
        if($tabSize === null)
        {
            if(isset($GLOBALS['Debug_HackerConsole_Main_LAST']))
            {
                $tabSize = $GLOBALS['Debug_HackerConsole_Main_LAST']->TAB_SIZE;
            }
            else
            {
                $tabSize = 4;
            }
        }
        $text = HackerConsole::expandTabs($text, $tabSize);
        $text = str_replace(' ', '&nbsp;', $text);
        $text = nl2br($text);

        return $text;
    }


    /**
     *  We need manual custom print_r() to use it in OB handlers
     * (original print_r() cannot work inside OB handler).
     */
    public static function print_r($obj, $no_print = 0, $level = 0)
    {
        if($level < 10)
        {
            if(is_array($obj))
            {
                $type = 'Array[' . count($obj) . ']';
            }
            elseif(is_object($obj))
            {
                $type = 'Object';
            }
            elseif(gettype($obj) == 'boolean')
            {
                $type = $obj ? 'TRUE' : 'FALSE';
            }
            elseif($obj === null)
            {
                $type = 'NULL';
            }
            else
            {
                $type = preg_replace("/\r?\n/", "\\n", $obj);
            }
            $buf = $type;
            if(is_array($obj) || is_object($obj))
            {
                $leftSp = str_repeat('    ', $level + 1);
                for(reset($obj); list($k, $v) = each($obj);)
                {
                    if($k === 'GLOBALS')
                    {
                        continue;
                    }
                    $buf .= "\n{$leftSp}[$k] => " . self::print_r($v, $no_print, $level + 1);
                }
            }
        }
        else
        {
            $buf = '*RECURSION*';
        }
        $buf = str_replace("\x00", ' ', $buf); // PHP5 private methods contain \x00 in names
        if($no_print)
        {
            return $buf;
        }
        else
        {
            echo $buf;
        }

        return null;
    }


    /**
     * string expandTabs($text, $tabSize=4)
     * Correctly convert tabulators to spaces.
     */
    public function expandTabs($text, $tabSize = 4)
    {
        $GLOBALS['expandTabs_tabSize'] = $tabSize;
        while(1)
        {
            $old = $text;
            // $text = preg_replace_callback('/^([^\t\r\n]*)\t(\t*)/m', array(&$this, 'expandTabs_callback'), $text);
            $text = preg_replace_callback('/^([^\t\r\n]*)\t(\t*)/m',
                                          ['lib\Debug\HackerConsole\HackerConsole', 'expandTabs_callback'], $text);
            if($old === $text)
            {
                return $text;
            }
        }
    }

    /**
     * @param $m
     *
     * @return string
     */
    public function expandTabs_callback($m)
    {
        $tabSize = $GLOBALS['expandTabs_tabSize'];
        $n       =
            (int)((strlen($m[1]) + $tabSize) / $tabSize) * $tabSize
            - strlen($m[1])
            + strlen($m[2]) * $tabSize;

        return $m[1] . str_repeat(' ', $n);
    }


    /**
     * Internal methods.
     */
    public function obHandler($s)
    {
        return $this->attachToHtml($s);
    }


    /**
     * @param $a
     *
     * @return string
     */
    public function _toJs($a)
    {
        $a = addslashes($a);
        $a = str_replace(["\n", "\r", '>', '<'], ['\n', '\r', '\'+\'>', '<\'+\''], $a);

        return "'$a'";
    }


    /**
     * array debug_backtrace_smart($ignoresRe=null, $returnCaller=false)
     *
     * Return stacktrace. Correctly work with call_user_func*
     * (totally skip them correcting caller references).
     * If $returnCaller is true, return only first matched caller,
     * not all stacktrace.
     *
     * @version 2.03
     */
    public function debug_backtrace_smart($ignoresRe = null, $returnCaller = false)
    {
        if(!is_callable($tracer = 'debug_backtrace'))
        {
            return [];
        }
        $trace = $tracer();

        if($ignoresRe !== null)
        {
            $ignoresRe = "/^(?>{$ignoresRe})$/six";
        }
        $smart      = [];
        $framesSeen = 0;
        for($i = 0, $n = count($trace); $i < $n; $i++)
        {
            $t = $trace[$i];
            if(!$t)
            {
                continue;
            }

            // Next frame.
            $next = isset($trace[$i + 1]) ? $trace[$i + 1] : null;

            // Dummy frame before call_user_func* frames.
            if(!isset($t['file']))
            {
                $t['over_function'] = $trace[$i + 1]['function'];
                $t                  = $t + $trace[$i + 1];
                $trace[$i + 1]      = null; // skip call_user_func on next iteration
            }

            // Skip myself frame.
            if(++$framesSeen < 2)
            {
                continue;
            }

            // 'class' and 'function' field of next frame define where
            // this frame function situated. Skip frames for functions
            // situated in ignored places.
            if($ignoresRe && $next)
            {
                // Name of function "inside which" frame was generated.
                $frameCaller = (isset($next['class']) ? $next['class'] . '::' : '') .
                    (isset($next['function']) ? $next['function'] : '');
                if(preg_match($ignoresRe, $frameCaller))
                {
                    continue;
                }
            }

            // On each iteration we consider ability to add PREVIOUS frame
            // to $smart stack.
            if($returnCaller)
            {
                return $t;
            }
            $smart[] = $t;
        }

        return $smart;
    }
}


/**
 * Last created console.
 */
$GLOBALS['Debug_HackerConsole_Main_LAST'] = null;


if(!function_exists('debugHC'))
{
    /**
     * @param        $v
     * @param string $group
     */
    function debugHC($v, $group = 'message')
    {
        if(DEBUG_MODE && is_callable($f = ['lib\Debug\HackerConsole\HackerConsole', 'out']))
        {
            call_user_func($f, $v, $group);
        }
    }
}