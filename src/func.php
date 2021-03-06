<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 08.07.14
 * Time: 4:36
 */
use proxy\Db as Db;
use proxy\Session;

/**
 * ��� ������� ������ �� 2 �����
 *
 * @param $string
 * @param $minlength
 * @param $maxlen
 *
 * @return mixed
 */
function cutString($string, $minlength, $maxlen)
{

    $len         = (mb_strlen($string) > $maxlen) ? mb_strripos(mb_substr($string, $minlength, $maxlen), ' ') : $maxlen;
    $cutStr      = mb_substr($string, $minlength, $len);
    $cutStr_last = mb_substr($string, $len, mb_strlen($string));
    $str[0]      = $cutStr;
    $str[1]      = $cutStr_last;

    return $str;

}

/**
 * @param $path
 *
 * @return mixed
 */
function _include($path)
{
    /** @noinspection PhpIncludeInspection */
    return include preg_replace('/[/]+/', DIRECTORY_SEPARATOR, $path);
}

/**
 * @param $num
 * @param $p
 *
 * @return int|string
 */
function sklon($num, $p)
{
    if(!is_numeric($num))
    {
        return $num;
    }
    $numret = (int)$num;
    $num    = (int)abs($num);
    $name   = [
        'y' => ['���', '����', '���'],
        'm' => ['�����', '������', '�������'],
        'd' => ['����', '���', '����'],
        'h' => ['���', '����', '�����'],
        'i' => ['������', '������', '�����'],
        's' => ['�������', '�������', '������'],
    ];
    if(!array_key_exists($p, $name))
    {
        return $num;
    }
    $cases = [2, 0, 1, 1, 1, 2];
    $str   = $name[$p][($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];

    return $numret . ' ' . $str;
}

/** ��������� ��������������� � �������������
 *
 * @param int    $n �����
 * @param string $form1 ������������ �����: 1 �������
 * @param string $form2 ������������ �����: 2 �������
 * @param string $form5 ������������� �����: 5 ������
 *
 * @return string ���������� �����
 *
 *  echo pluralForm($i, '�����', '������', '�������');
 */
function pluralForm($n, $form1, $form2, $form5)
{
    $n  = abs($n) % 100;
    $n1 = $n % 10;
    if($n > 10 && $n < 20)
    {
        return $form5;
    }
    if($n1 > 1 && $n1 < 5)
    {
        return $form2;
    }
    if($n1 == 1)
    {
        return $form1;
    }

    return $form5;
}

/**
 *
 * sklonenie_slov($c, array('�����', '�����', '����'));
 *
 * @param $chislo
 * @param $slova
 *
 * @return mixed
 */
function sklonenie_slov($chislo, $slova)
{
    $keisi = [2, 0, 1, 1, 1, 2];

    return $slova[($chislo % 100 > 4 && $chislo % 100 < 20) ? 2 : $keisi[min($chislo % 10, 5)]];
}

/**
 * ��������� Post
 */
function post_var()
{
    foreach(func_get_args() as $key)
    {
        if(!empty($_POST[$key]))
        {
            $$key = $_POST[$key];
        }
        else
        {
            $$key = null;
        }
    }
}


/** ========================================================= */
/**
 * @param $password
 *
 * @return string
 */
function password_encrypt($password)
{
    $hash_format     = "$2y$10$";
    $salt_length     = 22;
    $salt            = generate_salt($salt_length);
    $format_and_salt = $hash_format . $salt;
    $hash            = crypt($password, $format_and_salt);

    return $hash;
}

/**
 * @param $length
 *
 * @return string
 */
function generate_salt($length)
{
    $unique_random_string   = md5(uniqid(mt_rand(), true));
    $base64_string          = base64_encode($unique_random_string);
    $modified_base64_string = str_replace('+', '.', $base64_string);
    $salt                   = substr($modified_base64_string, 0, $length);

    return $salt;
}

/** ================================================================ */
/**
 * array glean
 * ������� �� ������ �������� � 0
 * ����� : $array = array_filter ($array );
 */
/**
 * $ar = array_filter(
 * $ar,
 * function($el){ return !empty($el);}
 * );
 */

/**
 * ������� �� ������ ��������
 *
 * @param $array
 *
 * @return array
 */
function array_clean($array)
{
    return array_diff($array, [null]);
}

/**
 * If the file name exists, returns new file name with _number appended so you don't overwrite it.
 *
 *
 * @param $path
 * @param $filename
 *
 * @return string
 */
function file_newname($path, $filename)
{
    $pos = strrpos($filename, '.');
    $ext = substr($filename, $pos);
    if($pos)
    {
        $name = substr($filename, 0, $pos);
    }
    else
    {
        $name = $filename;
    }

    $newpath = $path . '/' . $filename;
    $newname = $filename;
    $counter = 0;
    while(file_exists($newpath))
    {
        $newname = $name . '_' . $counter . $ext;
        $newpath = $path . '/' . $newname;
        $counter++;
    }

    return $newname;
}

/**
 * @return string
 */
function Greeting()
{
    $hour = date('H');
    if($hour < 6)
    {
        return '������ ����';
    }
    if($hour < 12)
    {
        return '������ ����';
    }
    if($hour < 18)
    {
        return '������ ����';
    }
    if($hour <= 23)
    {
        return '������ �����';
    }

    return '������ ����';
}

/**
 * ������ �� �������� ����� ������������ ��������, �� Youtube ����� �������, � ��������� ������ �������
 *
 * @param $text
 *
 * @return mixed
 */
function makeLink($text)
{
    $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
    if(preg_match_all($reg_exUrl, $text, $output))
    {
        foreach($output[0] as $url)
        {
            $youtubePattern =
                '#(http://www.youtube.com)?/(v/([-|~_0-9A-Za-z]+)|watch\?v\=([-|~_0-9A-Za-z]+)&*(\/\S*)?)#i';
            if(preg_match($youtubePattern, $url, $youtubemathes))
            {
                $video_id = $youtubemathes[4];
                $embed    =
                    '<br /><object width="480" height="390"><param name="movie" value="http://www.youtube.com/v/' .
                    $video_id .
                    '&hl=en&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'
                    .
                    $video_id .
                    '&hl=en&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="390"></embed></object>';
                $text     = str_replace($url, $embed, $text);
            }
            else
            {
                $aHeader = get_headers($url, 1);
                if(array_key_exists('Content-Type', $aHeader)
                    && substr($aHeader['Content-Type'], 0, 6) == 'image/'
                )
                {
                    $img  = '<br /><a href="' . $url . '" rel="nofollow"><img src="' . $url . '" /></a>';
                    $text = str_replace($url, $img, $text);
                }
                else
                {
                    $link = '<a href="' . $url . '" rel="nofollow">' . $url . '</a>';
                    $text = str_replace($url, $link, $text);
                }
            }
        }
    }

    return $text;
}

/**
 * <?php echo hideEmail('myemail@mydomain.com'); ?>
 *
 * @param $email
 *
 * @return string
 */
function hideEmail($email)
{
    $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
    $key           = str_shuffle($character_set);
    $cipher_text   = '';
    $id            = 'e' . rand(1, 999999999);
    for($i = 0; $i < strlen($email); $i += 1)
    {
        $cipher_text .= $key[strpos($character_set, $email[$i])];
    }
    $script = 'var a="' . $key . '";var b=a.split("").sort().join("");var c="' . $cipher_text . '";var d="";';
    $script .= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
    $script .= 'document.getElementById("' . $id . '").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
    $script = "eval(\"" . str_replace(["\\", '"'], ["\\\\", '\"'], $script) . "\")";
    $script = '<script type="text/javascript">/*<![CDATA[*/' . $script . '/*]]>*/</script>';

    return '<span id="' . $id . '">[javascript protected email address]</span>' . $script;
}

/**
 * �������� e-mail � ������� ��������� ���������� PHP:
 *
 * send_mime_mail('����� ������',
 *                            'sender@site.ru',
 *                            '���������� ������',
 *                            'recepient@site.ru',
 *                            'CP1251',  // ���������, � ������� ��������� ������������ ������
 *                            'KOI8-R', // ���������, � ������� ����� ���������� ������
 *                            '������-�����������',
 *                           "������������, � ���� ���������!"
 *                );
 *
 * @param      $name_from
 * @param      $email_from
 * @param      $name_to
 * @param      $email_to
 * @param      $data_charset
 * @param      $send_charset
 * @param      $subject
 * @param      $body
 * @param bool $html
 * @param bool $reply_to
 *
 * @return bool
 */
function sendMimeMail($name_from, // ��� �����������
                      $email_from, // email �����������
                      $name_to, // ��� ����������
                      $email_to, // email ����������
                      $data_charset, // ��������� ���������� ������
                      $send_charset, // ��������� ������
                      $subject, // ���� ������
                      $body, // ����� ������
                      $html = false, // ������ � ���� html ��� �������� ������
                      $reply_to = false)
{
    $to      = mimeHeaderEncode($name_to, $data_charset, $send_charset) . ' <' . $email_to . '>';
    $subject = mimeHeaderEncode($subject, $data_charset, $send_charset);
    $from    = mimeHeaderEncode($name_from, $data_charset, $send_charset) . ' <' . $email_from . '>';
    if($data_charset !== $send_charset)
    {
        $body = iconv($data_charset, $send_charset, $body);
    }
    $headers = "From: $from\r\n";
    $type    = ($html) ? 'html' : 'plain';
    $headers .= "Content-type: text/$type; charset=$send_charset\r\n";
    $headers .= "Mime-Version: 1.0\r\n";
    if($reply_to)
    {
        $headers .= "Reply-To: $reply_to";
    }

    return mail($to, $subject, $body, $headers);
}

/**
 * ��������������� �������:
 *
 * @param $str
 * @param $data_charset
 * @param $send_charset
 *
 * @return string
 */
function mimeHeaderEncode($str, $data_charset, $send_charset)
{
    if($data_charset !== $send_charset)
    {
        $str = iconv($data_charset, $send_charset, $str);
    }

    return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
}

/**
 * @param string $year
 *
 * @return bool|int|string
 * @throws Exception
 */
function auto_copyright($year = 'auto')
{
    if((int)$year === 'auto')
    {
        $year = date('Y');
    }
    if((int)$year === date('Y'))
    {
        return (int)$year;
    }
    if((int)$year < date('Y'))
    {
        return (int)$year . ' - ' . date('Y');
    }
    if((int)$year > date('Y'))
    {
        return date('Y');
    }
    throw new Exception('error in "function auto_copyright" - not recognized "$year"');
}

/**
 * @param string $file Filepath
 * @param string $format dateformat
 *
 * @link http://www.php.net/manual/de/function.date.php
 * @link http://www.php.net/manual/de/function.filemtime.php
 * @return string|bool Date or Boolean
 */
function getFiledate($file, $format)
{
    if(is_file($file))
    {
        $filePath = $file;
        if(!realpath($filePath))
        {
            $filePath = $_SERVER['DOCUMENT_ROOT'] . $filePath;
        }
        $fileDate = filemtime($filePath);
        if($fileDate)
        {
            $fileDate = date("$format", $fileDate);

            return $fileDate;
        }

        return false;
    }

    return false;
}

/**
 * @param $file
 * ���������� �����
 *
 * @return mixed
 */
function getFileextension($file)
{
    return pathinfo($file, PATHINFO_EXTENSION);
}

/**
 * @param $file
 * ���������� �����
 *
 * @return mixed
 */
function get_Fileextension($file)
{
    return end(explode('.', $file));
}

/**
 * @param string $file Filepath
 * @param string $query Needed information (0 = width, 1 = height, 2 = mime-type)
 *
 * @return string Fileinfo
 */
function getImageinfo($file, $query)
{
    if(!realpath($file))
    {
        $file = $_SERVER['DOCUMENT_ROOT'] . $file;
    }
    $image = getimagesize($file);

    return $image[$query];
}

/**
 * @return object
 */
function db()
{
    return Db::init();
}

/**
 * ���������� ��������� � ������� htmlspecialchars
 *
 * @param        $str
 * @param        $quote
 * @param string $encoding
 *
 * @return string
 */
function my_htmlspecialchars($str, $quote, $encoding = 'cp1251')
{
    return htmlspecialchars($str, $quote, $encoding);
}

/**
 * $username = sterilize($_POST['username']);
 * $query = "SELECT * FROM users WHERE username = '$username'";
 *
 * @param      $input
 * @param bool $is_sql
 *
 * @return mixed|string
 */
function sterilize($input, $is_sql = false)
{
    $input = htmlentities($input, ENT_QUOTES);

    if(get_magic_quotes_gpc())
    {
        $input = stripslashes($input);
    }

    if($is_sql)
    {
        $input = mysql_real_escape_string($input);
    }

    $input = strip_tags($input);
    $input = str_replace('  ', "\n", $input);

    return $input;
}

/**
 * @param string $addr
 * @param string $code
 */
function main_redir($addr = '', $code = '302')
{
    if(empty($addr))
    {
        if(isset($_SERVER['HTTP_REFERER']))
        {
            header('location: ' . $_SERVER['HTTP_REFERER'], true, $code);
        }
        else
        {
            header('location: /index.php', true, $code);
        }
    }
    else
    {
        header('location: ' . $addr, true, $code);
    }

    exit();
}

/**
 *
 */
function admin_only()
{
    if(!isset($_SESSION['logged']))
    {
        echo ' <div class="title2"> �������� ,������ ������� �������� ������ ��� ��������������
 <br/><a href = "index.php" > �������</a></div> ';
    }
}

/**
 * @param $str
 *
 * @return bool
 */
function if_admin($str)
{
    if(Session::get('logged') && Session::get('admnews') === md5(login() . '///' . pass()))
    {
        return $str;
    }

    return false;
}

/**
 * @return mixed
 */
function login()
{
    Db::where('userId', 1);
    $login = Db::getOne(TBL_USERS, 'foreignKey');

    return $login['foreignKey'];
}


/**
 * @return mixed
 */
function pass()
{
    Db::where('userId', 1);
    $login = Db::getOne(TBL_USERS, 'token');

    return $login['token'];
}

/**
 * @param $array
 */
function pre($array)
{
    echo '<pre style="background-color: #cccccc; color: #010C01;" > ';
    print_r($array);
    echo '</pre>';
}

/**
 * @return array|bool
 */
function getConfig()
{
    $config = [];
    if(is_readable(__DIR__ . '/passwords.php'))
    {
        $handle = file(__DIR__ . '/passwords.php', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        array_shift($handle);
        if($handle)
        {
            foreach($handle as $data)
            {
                $rec = explode('=>', $data);
                if(count($rec) === 2)
                {
                    $config[trim($rec[0])] = trim($rec[1]);
                }
            }
        }
        else
        {
            trigger_error('����������� ���������� - �� ������� ������� ����!', E_USER_ERROR);

            return false;
        }
    }
    else
    {
        trigger_error('����������� ���������� - �� ������ ���� � �������!', E_USER_ERROR);

        return false;
    }

    return $config;
}

/**
 * @param $str
 * @param $repl_array
 *
 * @return mixed
 *
 *  echo _strtr('��������� ������� Samsung ������� �� ����� iphone',array(
 * '��������� �������'=>'���������',
 * 'samsung'=>'�������', '�����'=>'���������','�������'=>'�������',
 * 'Iphone'=>'�����'
 * )
 *                                                           );
 */
function _strtr($str, $repl_array)
{
    $keys   = array_map(function ($key)
    {
        return '#' . $key . '#i';
    }, array_keys($repl_array));
    $values = array_values($repl_array);

    return preg_replace($keys, $values, $str);
}

/** Remove white space, comments etc.*/
function clean_file($content)
{

    $content = preg_replace('/^\s+|\n|\r|\s+$/m', '', $content);
    $content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
    $content = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $content);
    $content = preg_replace(['(( )+{)', '({( )+)'], '{', $content);
    $content = preg_replace(['(( )+})', '(}( )+)', '(;( )*})'], '}', $content);
    $content = preg_replace(['(;( )+)', '(( )+;)'], ';', $content);
    $content = preg_replace(['(:( )+)', '(( )+:)'], ':', $content);
    $content = preg_replace('/(\s|)\,(\s|)/', ',', $content);

    return $content;

}

/**
 * @param $input
 *
 * @return mixed
 */
function cleanInput($input)
{
    $search = [
        '@<script[^>]*?>.*?</script>@si', // javascript
        '@<[\/\!]*?[^<>]*?>@si', // HTML ����
        '@<style[^>]*?>.*?</style>@siU', // ���� style
        '@<![\s\S]*?--[ \t\n\r]*>@' // �������������� �����������
    ];
    $output = preg_replace($search, '', $input);

    return $output;
}

// $text = preg_replace('/(\r\n){2,}/i', '\r\n\r\n', $text); �������� ������� �������� �����
// $value = preg_replace('/\s{2,}/', '', $value); ������ ������ �������


/**
 * @param $data
 *
 * @return mixed
 */
function sql_valid($data)
{
    $data = str_replace(["\\", "'", '"', "\x00", "\x1a", "\r", "\n"],
                        ["\\\\", "\'", '\"', "\\x00", "\\x1a", "\\r", "\\n"], $data);

    return ($data);
}

/**
 * ��� ���� ������
 *
 * @param $input
 *
 * @return bool|mixed
 */
function sanitize($input)
{

    if(is_array($input))
    {
        foreach($input as $var => $val)
        {
            $output[$var] = sanitize($val);
        }
    }
    else
    {
        if(get_magic_quotes_gpc())
        {
            $input = stripslashes($input);
        }
        $input  = cleanInput($input);
        $output = sql_valid($input);

    }

    return isset($output) ? $output : false;
}

/**
 * @param $str
 *
 * @return string
 */
function cp1251_utf8($str)
{
    if(mb_check_encoding($str, 'Windows-1251') && !mb_check_encoding($str, 'UTF-8'))
    {
        $str = mb_convert_encoding($str, 'UTF-8', 'Windows-1251'); // �� Windows-1251 � UTF-8
    }

    return $str;
}

/**
 * @param $str
 *
 * @return string
 */
function utf8_cp1251($str)
{
    if(!mb_check_encoding($str, 'Windows-1251') && mb_check_encoding($str, 'UTF-8'))
    {
        $str = mb_convert_encoding($str, 'Windows-1251', 'UTF-8'); // �� UTF-8 � Windows-1251
    }

    return $str;
}

/**
 * @param $string
 *
 * @return string
 */
function utf8($string)
{
    return iconv('cp1251', 'utf-8', $string);
}

/**
 * @param $string
 *
 * @return string
 */
function cp1251($string)
{
    return iconv('utf-8', 'cp1251//IGNORE', $string);
}


/**
 * @param $str
 * @param $type
 *
 * @return mixed
 */
function WinUtf($str, $type) // $type: 'w' - encodes from UTF to win 'u' - encodes from win to UTF
{
    static $conv = '';
    if(!is_array($conv))
    {
        $conv = [];
        for($x = 128; $x <= 143; $x++)
        {
            $conv['utf'][] = chr(209) . chr($x);
            $conv['win'][] = chr($x + 112);
        }
        for($x = 144; $x <= 191; $x++)
        {
            $conv['utf'][] = chr(208) . chr($x);
            $conv['win'][] = chr($x + 48);
        }
        $conv['utf'][] = chr(208) . chr(129);
        $conv['win'][] = chr(168);
        $conv['utf'][] = chr(209) . chr(145);
        $conv['win'][] = chr(184);
    }
    if($type == 'w')
    {
        return str_replace($conv['utf'], $conv['win'], $str);
    }
    elseif($type == 'u')
    {
        return str_replace($conv['win'], $conv['utf'], $str);
    }
    else
    {
        return $str;
    }
}

/**
 *
 * @param $path
 *
 * @return array|bool
 * array [ dirname, basename, extension, filename ]
 * � ���������� ��������� UTF-8
 */
function path_info($path)
{
    if(strpos($path, '/') !== false)
    {
        $basename = explode('/', $path);
        $basename = end($basename);
    }
    else if(strpos($path, '\\') !== false)
    {
        $basename = explode('\\', $path);
        $basename = end($basename);
    }
    else
    {
        return false;
    }
    if(!$basename)
    {
        return false;
    }
    $dirname = substr($path, 0, strlen($path) - strlen($basename) - 1);
    if(strpos($basename, '.') !== false)
    {
        $extension = explode('.', $path);
        $extension = end($extension);
        $filename  = substr($basename, 0, strlen($basename) - strlen($extension) - 1);
    }
    else
    {
        $extension = '';
        $filename  = $basename;
    }

    return [
        'dirname'   => $dirname,
        'basename'  => $basename,
        'extension' => $extension,
        'filename'  => $filename,
    ];
}


/**
 *
 *  basename ��� ������� � utf-8 �������������� ��������� Windows-1251
 *
 * @param      $param
 *
 * @param null $suffix
 *
 * @return mixed|string
 */
function __basename($param, $suffix = null)
{

    $basename = strrpos($param, DIRECTORY_SEPARATOR);
    $basename = substr($param, $basename);
    $basename = ltrim($basename, DIRECTORY_SEPARATOR);

    if($suffix)
    {

        if((strpos($param, $suffix) + strlen($suffix)) === strlen($param))
        {
            return str_ireplace($suffix, '', $basename);
        }
        else
        {
            return $basename;
        }
    }
    else
    {
        return $basename;
    }
}

/**
 * @param $path
 *
 * @return mixed|null
 */
function basename_utf8($path)
{

    if(strpos($path, '/') !== false)
    {
        return end(explode('/', $path));
    }
    elseif(strpos($path, '\\') !== false)
    {
        return end(explode('\\', $path));
    }
    else
    {
        return null;
    }
}

/**
 * ������� �������� ���������
 *
 * @param $string
 *
 * @return string $string
 */
function detect_encoding($string)
{
    $string = implode(glob($string));
    static $list = ['utf-8', 'windows-1251'];

    foreach($list as $item)
    {
        $sample = iconv($item, ($item . '//IGNORE'), $string);
        if(md5($sample) == md5($string))
        {
            return $item;
        }
    }

    return '�������� ������� �� ��������';
}

/**
 *  ������������� basename
 *
 * @param $path
 *
 * @return string
 */
function _basename($path)
{

    if(detect_encoding($path) == 'windows-1251')
    {
        return basename($path);
    }
    else
    {
        return ltrim(substr($path, strrpos($path, DIRECTORY_SEPARATOR)), DIRECTORY_SEPARATOR);
    }
}

/**
 * �������������� ������� � ����������
 *
 * @param $multiarray
 *
 * @return array
 */
function flat_array($multiarray)
{
    $result   = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($multiarray));

    foreach($iterator as $value)
    {
        $result[] = $value; // ��� ����������� ��� ��� �������
    }

    return $result;
}

/**
 * ����� �� ��������� �������� ��������������, ���� ��� �� ������� - �� �� ���� ���������
 * $dir = 'files/portfolio/';
 * $ok_subdir = array( 'thumb' ); // � ����� �������������� ������ �����
 * $mask = '*.jpg'; // ����� ������
 * $thumb = recursive_dir( $dir, $mask, $ok_subdir );
 *
 * @param        $dir - ����� ������
 * @param string $mask - ����� ����������� ������
 * @param array  $no_subdir - ����� ����� ������ ���������
 * @param bool   $multi_arrau - ������������� ������ � ���������� ������
 * @param array  $ok_subdir - � ����� ��������������� ������
 *
 * @return array
 */
function recursive_dir($dir, $mask = '.jpg', $ok_subdir = [], $no_subdir = [], $multi_arrau = true)
{
    static $arr = [];
    $cont = glob($dir . '/*');
    if(count($cont))
    {
        $name_subdir = basename($dir);
        foreach($cont as $file)
        {
            if(in_array($name_subdir, $ok_subdir, true) || !count($ok_subdir))
            {
                if(!in_array($name_subdir, $no_subdir, true))
                {
                    if(is_dir($file))
                    {
                        recursive_dir($file, $mask, $ok_subdir, $no_subdir, $multi_arrau);
                    }
                    else
                    {
                        if(strpos($file, $mask) !== false)
                        {
                            if($multi_arrau)
                            {
                                $arr[$name_subdir][] = $file;
                            }
                            else
                            {
                                $name_dir = explode('/', $file);
                                $arr[]    = [$name_dir[2], $file];
                            }
                        }
                    }
                }
            }
            elseif(is_dir($file))
            {
                $cont_subdir = glob($file . '/*', GLOB_ONLYDIR);
                if(count($cont_subdir))
                {
                    foreach($cont_subdir as $file2)
                    {
                        recursive_dir($file2, $mask, $ok_subdir, $no_subdir, $multi_arrau);
                    }
                }
            }
        }
    }

    return $arr;
}


/**
 * ����� �� ��������� �������� ��������������, ���� ��� �� ������� - �� �� ���� ���������
 * $dir = 'files/portfolio/';
 * $ok_subdir = array( 'thumb' ); // � ����� �������������� ������ �����
 * $mask = '*.jpg'; // ����� ������
 * $thumb = recursive_dir( $dir, $mask, $ok_subdir );
 *
 * @param        $dir - ����� ������
 * @param string $mask - ����� ����������� �����
 * @param bool   $multi_arrau - ������������� ������ � ���������� ������
 * @param array  $ok_subdir - � ����� ��������������� ������
 *
 * @return array
 */
function recursive_dir2($dir, $mask = '*', $ok_subdir = [], $multi_arrau = true)
{
    static $thumb = [];
    $skan = glob($dir . $mask);
    if(in_array(basename($dir), $ok_subdir))
    {
        if($skan)
        {
            $thumb[] = $skan;
        }
    }
    elseif(empty($ok_subdir))
    {
        if($skan)
        {
            $thumb[] = $skan;
        }
    }
    foreach(glob($dir . '*', GLOB_ONLYDIR) as $subdir)
    {
        if($subdir)
        {
            $skan = glob($subdir . '/' . $mask);
            if(in_array(basename($subdir), $ok_subdir))
            {
                if($skan)
                {
                    $thumb[] = $skan;
                }
            }
            elseif(empty($ok_subdir))
            {
                if($skan)
                {
                    $thumb[] = $skan;
                }
            }
            $subsubdir = glob($subdir . '/*', GLOB_ONLYDIR);
            if(count($subsubdir))
            {
                foreach($subsubdir as $subdir2)
                {
                    recursive_dir2($subdir2 . '/', $mask, $ok_subdir);
                }
            }
        }
    }
    if($multi_arrau)
    {
        return $thumb;
    }
    else
    {
        $result = [];
        array_walk_recursive($thumb, function ($value) use (&$result)
        {
            $result[] = $value; // ��� ����������� ��� ��� �������
        });

        return $result;
    }

}


/**
 * @param     $input
 * @param int $i
 *
 * @return array
 * ��������� ����� �������� ������� � �������� ����������� ���������
 */
function my_array_rand($input, $i = 2)
{
    if($i > count($input))
    {
        $i = count($input);
    }
    srand((float)microtime() * 10000000);
    $rand_keys = array_rand($input, $i);
    $res       = [];
    if($i > 1)
    {
        for($a = 0; $a < $i; $a++)
        {
            $res[] = $input[$rand_keys[$a]];
        }
    }
    else
    {
        $res[] = $input[$rand_keys];
    }

    return $res;
}

/**
 * @param     $array
 * @param int $limit
 *
 * @return array ��������� ����� �������� ������� � �������� ����������� ���������
 * ��������� ����� �������� ������� � �������� ����������� ���������
 * @throws \Exception
 */
function get_random_elements($array, $limit = 0)
{
    if(is_array($array))
    {
        shuffle($array);
        if($limit > 0)
        {
            $array = array_splice($array, 0, $limit);
        }
    }
    else
    {
        throw new Exception('������ ���������� ������� "get_random_elements" ������ ���� ��������');
    }

    return $array;
}

/**
 * @param $folder
 * @param $space
 * ����� ������ ��������
 * ��������� ������� ��� �������� /files:
 * showTree("./files", "");
 */
function showTree($folder, $space)
{
    /* �������� ������ ������ ������ � ��������� ������ $folder */
    $files = scandir($folder);
    foreach($files as $file)
    {
        /* ����������� ������� � ������������ ������� */
        if(($file == '.') || ($file == '..'))
        {
            continue;
        }
        $f0 = $folder . '/' . $file; //�������� ������ ���� � �����
        /* ���� ��� ���������� */
        if(is_dir($f0))
        {
            /* �������, ����� �������� ������, �������� ���������� */
            echo $space . $file . "<br />";
            /* � ������� �������� ������� ���������� ���������� ���������� */
            showTree($f0, $space . '&nbsp;&nbsp;&nbsp;&nbsp;');
        } /* ���� ��� ����, �� ������ ������� �������� ����� */
        else
        {
            echo $space . $file . "<br />";
        }
    }
}

/**
 * @param $text_post
 *
 * @return mixed
 */
function replaceBBCode($text_post)
{
    $str_search  = [
        "#\[del\](.+?)\[\/del\]#is",
        "#\[komm\](.+?)\[\/komm\]#is",
        "#\[y\](.+?)\[\/y\]#is",
        //        "#\\\n#is",
        "#\[b\](.+?)\[\/b\]#is",
        "#\[i\](.+?)\[\/i\]#is",
        "#\[u\](.+?)\[\/u\]#is",
        "#\[code\](.+?)\[\/code\]#is",
        "#\[quote\](.+?)\[\/quote\]#is",
        "#\[url=(.+?)\](.+?)\[\/url\]#is",
        "#\[url\](.+?)\[\/url\]#is",
        "#\[img\](.+?)\[\/img\]#is",
        "#\[size=(.+?)\](.+?)\[\/size\]#is",
        "#\[color=(.+?)\](.+?)\[\/color\]#is",
        "#\[list\](.+?)\[\/list\]#is",
        "#\[listn](.+?)\[\/listn\]#is",
        "#\[\*\](.+?)\[\/\*\]#",
    ];
    $str_replace = [
        "",
        "<p class='komment'>\\1</p>",
        "<span class='date'>\\1</span>",
        // "<br />",
        "<b>\\1</b>",
        "<i>\\1</i>",
        "<span style='text-decoration:underline'>\\1</span>",
        "<code class='code'>\\1</code>",
        "<table width = '95%'><tr><td>������</td></tr><tr><td class='quote'>\\1</td></tr></table>",
        "<a href='\\1'>\\2</a>",
        "<a href='\\1'>\\1</a>",
        "<img src='\\1' alt = '�����������' />",
        "<span style='font-size:\\1%'>\\2</span>",
        "<span style='color:\\1'>\\2</span>",
        "<ul>\\1</ul>",
        "<ol>\\1</ol>",
        "<li>\\1</li>",
    ];

    return preg_replace($str_search, $str_replace, $text_post);
}

/**
 * @return string
 */
function ip()
{
    if($_SERVER)
    {
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'])
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        elseif(isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'])
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        else
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    }
    else
    {
        if(getenv('HTTP_X_FORWARDED_FOR'))
        {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        }
        elseif(getenv('HTTP_CLIENT_IP'))
        {
            $ip = getenv('HTTP_CLIENT_IP');
        }
        else
        {
            $ip = getenv('REMOTE_ADDR');
        }
    }

    return $ip;
}

/**
 * @param $myIP
 *
 * @return string
 */
function detect_proxy($myIP)
{
    $scan_headers = [
        'HTTP_VIA',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_FORWARDED',
        'HTTP_CLIENT_IP',
        'HTTP_FORWARDED_FOR_IP',
        'VIA',
        'X_FORWARDED_FOR',
        'FORWARDED_FOR',
        'X_FORWARDED',
        'FORWARDED',
        'CLIENT_IP',
        'FORWARDED_FOR_IP',
        'HTTP_PROXY_CONNECTION',
    ];

    $flagProxy = false;
    $libProxy  = 'No';

    foreach($scan_headers as $i)
    {
        if($_SERVER[$i])
        {
            $flagProxy = true;
        }
    }

    if(in_array($_SERVER['REMOTE_PORT'], [8080, 80, 6588, 8000, 3128, 553, 554])
        || @fsockopen($_SERVER['REMOTE_ADDR'], 80, $errno, $errstr, 30)
    )
    {
        $flagProxy = true;
    }

    // Proxy LookUp
    if($flagProxy == true && isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])
    )
        // Transparent Proxy
        // REMOTE_ADDR = proxy IP
        // HTTP_X_FORWARDED_FOR = your IP
    {
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])
            && $_SERVER['HTTP_X_FORWARDED_FOR'] == $myIP
        )
        {
            $libProxy = 'Transparent Proxy';
        }
        // Simple Anonymous Proxy
        // REMOTE_ADDR = proxy IP
        // HTTP_X_FORWARDED_FOR = proxy IP
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])
            && $_SERVER['HTTP_X_FORWARDED_FOR'] == $_SERVER['REMOTE_ADDR']
        )
        {
            $libProxy = 'Simple Anonymous (Transparent) Proxy';
        }
        // Distorting Anonymous Proxy
        // REMOTE_ADDR = proxy IP
        // HTTP_X_FORWARDED_FOR = random IP address
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])
            && $_SERVER['HTTP_X_FORWARDED_FOR'] != $_SERVER['REMOTE_ADDR']
        )
        {
            $libProxy = 'Distorting Anonymous (Transparent) Proxy';
        }
        // Anonymous Proxy
        // HTTP_X_FORWARDED_FOR = not determined
        // HTTP_CLIENT_IP = not determined
        // HTTP_VIA = determined
        else if($_SERVER['HTTP_X_FORWARDED_FOR'] == '' && $_SERVER['HTTP_CLIENT_IP'] == ''
            && !empty($_SERVER['HTTP_VIA'])
        )
        {
            $libProxy = 'Anonymous Proxy';
        }
        // High Anonymous Proxy
        // REMOTE_ADDR = proxy IP
        // HTTP_X_FORWARDED_FOR = not determined
        else
        {
            $libProxy = 'High Anonymous Proxy';
        }
    }

    return $libProxy;
}

/**
 * ������ ���� �� ������� �������
 *
 * resize(800, $img_target, $img_original);
 *
 * @param $newWidth
 * @param $targetFile
 * @param $originalFile
 *
 * @return bool
 * @throws
 */
function resize($newWidth, $targetFile, $originalFile)
{
    $info = getimagesize($originalFile);
    $mime = $info['mime'];
    switch($mime)
    {
        case 'image/jpeg':
            $image_create_func = 'imagecreatefromjpeg';
            $image_save_func   = 'imagejpeg';
            $new_image_ext     = 'jpg';
            break;
        case 'image/png':
            $image_create_func = 'imagecreatefrompng';
            $image_save_func   = 'imagepng';
            $new_image_ext     = 'png';
            break;
        case 'image/gif':
            $image_create_func = 'imagecreatefromgif';
            $image_save_func   = 'imagegif';
            $new_image_ext     = 'gif';
            break;
        default:
            throw new Exception('Unknown image type.');
    }
    $img = $image_create_func($originalFile);
    list($width, $height) = getimagesize($originalFile);
    if((!$width) || (!$height))
    {
        $GLOBALS['errors'][] = 'Image couldn\'t be resized because it wasn\'t a valid image.';

        return false;
    }
    if(($width <= $newWidth) && ($height <= $newWidth))
    {
        return false;
    } //no resizing needed
    //try max width first...
    $ratio = $newWidth / $width;
    $new_w = $newWidth;
    $new_h = $height * $ratio;
    //if that didn't work
    if($new_h > $newWidth)
    {
        $ratio = $newWidth / $height;
        $new_h = $newWidth;
        $new_w = $width * $ratio;
    }
    $tmp = imagecreatetruecolor($new_w, $new_h);
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $new_w, $new_h, $width, $height);
    if(file_exists($targetFile))
    {
        unlink($targetFile);
    }
    $image_save_func($tmp, "$targetFile.$new_image_ext");

    return true;
}


/**
 * ��������  ������ � �����
 *
 * $salt="123!#&%asgfHTA";
 * $pass="proba";
 *
 * @param $pass
 * @param $salt
 *
 * @return string
 */
function my_md5($pass, $salt)
{
    $spec    = ['~', '!', '@', '#', '$', '%', '^', '&', '*', '?'];
    $crypted = md5(md5($salt) . md5($pass));
    $c_text  = md5($pass);
    $temp    = '';
    for($i = 0; $i < strlen($crypted); $i++)
    {
        if(ord($c_text[$i]) >= 48 and ord($c_text[$i]) <= 57)
        {
            @$temp .= $spec[$c_text[$i]];
        }
        elseif(ord($c_text[$i]) >= 97 and ord($c_text[$i]) <= 100)
        {
            @$temp .= strtoupper($crypted[$i]);
        }
        else
        {
            @$temp .= $crypted[$i];
        }
    }

    return md5($temp);
}

/**
 * ������� �������� ������ �� ���������� ����� ������������ ����� �� ��������
 *
 * @param $dir_path
 * @param $times
 */
function old($dir_path, $times)
{
    $old_time = time() - 60 * $times;
    $dir      = opendir($dir_path);
    $files    = [];
    $time     = [];
    $file     = readdir($dir);
    while($file)
    {
        if(($file != '.') && ($file != '..'))
        {
            $files[] = $dir_path / $file;
        }
        $time[] = filemtime($dir_path / $file);
    }
    closedir($dir);
    $count_files = count($files);
    for($i = 1; $i < $count_files; $i++)
    {
        if($time[$i] <= $old_time)
        {
            @unlink($files[$i]);
        }
    }
}

/**
 * ������� ��������� ���� ���������� ��������
 *
 * @param      $text
 * @param null $br
 *
 * @return mixed
 */
function EscText($text, $br = null) // �������� ��� ���������� �������
{
    if($br != null)
    {
        for($i = 0; $i <= 31; $i++)
        {
            $text = str_replace(chr($i), null, $text);
        }
    }
    else
    {
        for($i = 0; $i < 10; $i++)
        {
            $text = str_replace(chr($i), null, $text);
        }
        for($i = 11; $i < 20; $i++)
        {
            $text = str_replace(chr($i), null, $text);
        }
        for($i = 21; $i <= 31; $i++)
        {
            $text = str_replace(chr($i), null, $text);
        }
    }

    return $text;
}

/**
 * ������� ����������� �������
 *
 * @param null $time
 *
 * @return bool|mixed|string
 */
function VrTime($time = null)
{
    if($time == null)
    {
        $time = time();
    }
    $timep     = date('j M Y � H:i', $time) . '' . '';
    $time_p[0] = date('j n Y', $time);
    $time_p[1] = date('H:i', $time);
    if($time_p[0] == date('j n Y'))
    {
        $timep = '������� � ' . date('H:i:s', $time);
    }
    if($time_p[0] == date('j n Y', time() - 60 * 60 * 24))
    {
        $timep = "����� � $time_p[1]";
    }
    $timep = str_replace(['Jan', 'Feb', 'Mar', 'May', 'Apr', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                         ['���', '���', '���', '���', '���', '���', '���', '���', '���', '���', '���', '���'], $timep);

    return $timep;
}

/**
 * FTP + CURL
 *
 * @param $ftpLogin
 * @param $ftpPass
 * @param $ftpAddr
 * @param $ftpPath
 * @param $ftpFile
 *
 * @return mixed
 */
function UploadFTP($ftpLogin, $ftpPass, $ftpAddr, $ftpPath, $ftpFile)
{
    $remoteurl = "ftp://${ftpLogin}:${ftpPass}@${ftpAddr}${ftpPath}/${ftpFile}";
    $ch        = curl_init();
    $fp        = fopen($ftpFile, 'rb');
    curl_setopt($ch, CURLOPT_URL, $remoteurl);
    curl_setopt($ch, CURLOPT_UPLOAD, 1);
    curl_setopt($ch, CURLOPT_INFILE, $fp);
    curl_setopt($ch, CURLOPT_INFILESIZE, filesize($ftpFile));
    $error = curl_exec($ch);
    curl_close($ch);

    return $error;
}


/**
 *
 * echo cp1251(translate("Hello world!"));
 *
 * ��� �������� � �������� �� ����������, � ���������� hl, sl, tl ����� �������� ru �� en � ��������.
 *
 * @param $_str
 *
 * @return mixed
 */
function translate($_str)
{
    $curlHandle = curl_init();
    // options
    $postData           = [];
    $postData['client'] = 't';
    $postData['text']   = $_str;
    $postData['hl']     = 'ru';
    $postData['sl']     = 'en';
    $postData['tl']     = 'ru';
    curl_setopt($curlHandle, CURLOPT_URL, 'http://translate.google.com/translate_a/t');
    curl_setopt($curlHandle, CURLOPT_HTTPHEADER, [
        'User-Agent: Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.1.4) Gecko/20091016 Firefox/3.5.4',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language: ru,en-us;q=0.7,en;q=0.3',
        'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7',
        'Keep-Alive: 300',
        'Connection: keep-alive',
    ]);
    curl_setopt($curlHandle, CURLOPT_HEADER, 0);
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlHandle, CURLOPT_TIMEOUT, 10);
    curl_setopt($curlHandle, CURLOPT_POST, 0);
    if($postData !== false)
    {
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, http_build_query($postData));
    }

    $content = curl_exec($curlHandle);
    curl_close($curlHandle);
    $content = str_replace(',,', ',"",', $content);
    $content = str_replace(',,', ',"",', $content);
    $result  = json_decode($content);

    return $result[0][0][0];
}

/**
 *  ��� ajax ������� ��� ������ true, � ��� �������� ������� false.
 *
 * Check if current request is AJAX.
 */
function is_ajax()
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

/**
 * @param        $file
 * @param string $name
 * @param string $type
 * @param int    $del
 *
 * ������ ����� ������� �������� ���� � ������ ������������,
 * ���� �� �������� ���� ����� ������ ���� "�� ����" ����� �� �������.
 * ������:
 * file_dload('dir/dir/file.zip', '���� �������� �� ����.zip', 'application/zip', 1);
 * file_dload('dir/dir/file.txt', '���� �������� �� ����.txt');
 *
 * @return bool
 */
function file_dload($file, $name = 'test.txt', $type = 'application/octet-stream', $del = 1)
{
    ob_end_clean();
    ob_start();
    header('Content-Description: File Transfer');
    header('Content-Type: ' . $type);
    header('Content-Disposition: attachment; filename=' . $name);
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    if($del)
    {
        unlink($file);
    }
    flush();

    return true;
}

/**
 * debug($var); // <pre>1</pre>
 *
 * @param $var
 */
function debugVar($var)
{
    $tpl = php_sapi_name() !== 'cli' ? '<pre>%s</pre>' : "\n%s\n";
    printf($tpl, print_r($var, true));
}

/**
 * @param        $url
 * @param string $alt
 *
 * ����������� ����������� ���������� MIME base64
 * Example:
 * echo image64('http://annimon.com/theme/default/images/logo.png'); // �������� ������������ ����������� ;)
 *
 * @return string
 */
function image64($url, $alt = '')
{
    /**
     * �������� ������������ ��� �����������,
     * ������ ���������� ����� � ������,
     * �������� ������ ���������� MIME base64,
     * ����� �������� ������ �� ��������� � ������� ������� �� ������ � ����� ������
     */
    $extension = pathinfo($url, PATHINFO_EXTENSION);

    return (in_array($extension, ['gif', 'jpg', 'jpeg', 'png'])) ?
        '<img src="data:image/' . $extension . ';base64,' . trim(chunk_split(base64_encode(file_get_contents($url)))) .
        '" alt="' . $alt . '" />' : '������ �� ������ ��� �����������.';
}

/**
 * @param $s
 * @param $k
 *
 * $str = 124;
 * echo endofstr($str, 'card');
 *
 * @return string
 */
function endofstr($s, $k)
{
    $v   = (int)($s);
    $len = strlen($v);
    $arr = [
        'sec'  => ['������', '�������', '�������'],
        'min'  => ['�����', '������', '������'],
        'hor'  => ['�����', '���', '����'],
        'day'  => ['����', '����', '���'],
        'mon'  => ['�������', '�����', '������'],
        'year' => ['���', '���', '����'],
        'ammo' => ['��������', '������', '�������'],
        'card' => ['����', '�����', '�����'],
    ];
    $s   = $len <= 2 ? (int)($s) : substr($s, ($len - ($len - 2)));
    $s   = ($s > 14) ? substr($s, -1) : intval($s);
    $s   = ($s > 0 && $s < 3) ? (int)($s) : ($s > 2 && $s < 5 ? 2 : 0);

    return $v . ' ' . $arr[$k][$s] . ' ';
}

/**
 * @param $text
 *
 * ��������������� ������� antispam($text)
 *
 * @return string
 */
function antispam_help($text)
{
    $dom = ['www\.', 'wap\.']; //���������
    if(preg_match('#^(https?|ftp)://(' . implode('|', $dom) . ')?' .
                  str_replace('.', '\.', $_SERVER['SERVER_NAME']) . '#',
                  $text[0]))
    {
        return '<a href="' . $text[0] . '">' . $text[0] . '</a>';
    }
    else
    {
        return '[�������]';
    }
}

/**
 * @param $text
 *
 *  ������� ���������. �������� ��� ����� ������ �� ����� [�������], � ��������� ������������.
 *
 * @return mixed
 */
function antispam($text)
{
    return preg_replace_callback("#(https?|ftp)://\S+[^\s.,>)\];'\"!?]#", 'antispam_help', $text);
}

/**
 * @param $d
 *
 * ����� ���� ���������� ������� ������
 *
 * @return string
 */
function rusdate($d)
{
    $montharr = [
        '������', '�������', '�����', '������', '���', '����', '����', '�������', '��������', '�������', '������',
        '�������',
    ];
    $i        = date('m', $d) - 1;

    return date('j', $d) . " $montharr[$i] " . date('Y', $d);
}

/**
 * ����������� ������ �������� ����������
 * �����������, �������� ������ � ���, ��� ������ �� ��� �������
 */
function user_phone()
{
    //��������� ������� ������� ���������
    $phone_headers = [
        'HTTP_MSISDN',
        'HTTP_X_MSISDN',
        'HTTP_X_NOKIA_MSISDN',
        'HTTP_X_WAP_NETWORK_CLIENT_MSISDN',
        'HTTP_X_UP_CALLING_LINE_ID',
        'HTTP_X_NETWORK_INFO',
    ];
    $phone         = '';

    foreach($phone_headers as $key)
    {
        if(isset($_SERVER[$key]))
        {
            $arr = [];
            //������� ����� ������� �� �����
            preg_match('`\d{5,20}`', $_SERVER[$key], $arr);
            if($arr[0])
            {
                $phone = $arr[0];
                break;
            }
        }
    }

    return $phone;
}


/**
 * @param $file_name
 *
 * ��������� ���������� ��������� � ��������� ���������
 * �������� ����� �� ��, ��� ��� ������� � ���������� ����������
 * ��������� ���������� ����� � ���� ����� ������ ������, �� ������� ����� ��������� ����
 *
 * @return array
 */
function getSizePhotoMobile($file_name)
{
    // �������� EXIF-���������
    $exif_read_data = @exif_read_data($file_name);
    $size           = @getimagesize($file_name);
    $width          = $size[0];
    $height         = $size[1];
    $degree         = 0;
    // ���� ��������� ��������, � ����� ��� ������� ���������� �� ����������
    if($exif_read_data)
    {
        if(isset($exif_read_data['Orientation']) AND $exif_read_data['Orientation'] > 4)
        {
            $size   = getimagesize($file_name, $info);
            $width  = $size[1];
            $height = $size[0];
            switch($exif_read_data['Orientation'])
            {
                case 5:
                    $degree = 270;
                    break;
                case 6:
                    $degree = 270;
                    break;
                case 7:
                    $degree = 90;
                    break;
                case 8:
                    $degree = 90;
                    break;
            }
        }
    }

    return [
        0        => $width,
        1        => $height,
        'degree' => $degree,
    ];
}

/**
 * @param $img
 * @param $degree
 *
 * @return bool|resource
 *
 * ������ ������� �������� ��������
 * $size = getSizePhotoMobile($source);
 * ���� ��� ���� ������� � ���������� ��������
 * if($size['degree'] > 0) {
 * $photo = rotatePhotoMobile($source, $size['degree']);
 * } else {
 * ��� ��� ������� �����
 * }
 *
 */
function rotatePhotoMobile($img, $degree)
{
    // �������� ������ � ��������
    $size = getimagesize($img);
    //���������� ��� (����������) ��������
    $format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
    $icfunc = 'imagecreatefrom' . $format;   //����������� ������� ��� ���������� �����
    //���� ��� ����� �������, �� ���������� ������ �������
    if(!function_exists($icfunc))
    {
        return false;
    }
    // �������� �����������
    $source = $icfunc($img);
    // �������. ������ ���� �������� ������ 0xffffff
    $rotate = imagerotate($source, $degree, '0xd72630');

    return $rotate;
}

/**
 * ���������� �� ��� �������� ����
 *
 * @param $url - string URL �����
 *
 * @return string ��������� ��������
 *
 * $url = 'http://vk-book.ru';
 * $result = getPoweredBy($url);
 * print_r ($result);
 *
 */
function getPoweredBy($url)
{
//	$tmp = parse_url($url);
    $stream = @fopen($url, 'rb'); // ��������� ����
    if(!$stream)
    {
        return '���� �� ��������!';
    }
    $array = stream_get_meta_data($stream); // �������� ���������
    $info  = false;
    // ������� ���������� � X-Powered-By
    foreach($array['wrapper_data'] as $k => $v)
    {
        if(strpos($v, 'X-Powered-By:') !== false)
        {
            $info = explode('X-Powered-By:', $v);
        }
    }
    // ������ ���������
    if($info)
    {
        $powered_by = trim($info[1]);

        return $powered_by;
    }
    else
    {
        return '�� ��������!';
    }
}

/**
 * ������� ��� �������� ������������� ob_* �������
 *
 * @param $fn
 *
 * @return string
 *
 * $v = ob(function(){
 * echo "Hello, world!";
 * });
 *
 * echo ">$v<";
 * >Hello, world!<
 *
 */
function ob($fn)
{
    ob_start();
    $fn();

    return ob_get_clean();
}

/**
 *  ������� ������� ��� ������ �������� ���������� �������� ����.
 *  ��� ������������� - �������� tick_time("�������� �����-��") �� ������ ����������� ����� ����
 *  � tick_time("��������� ��������") ��� tick_time() ����� ����� ������� ����. � ���������� ����� ��������
 *  ���������� ������ ���������� � ��������� �� 4-�� �����.
 *  ������:
 *  tick_time("��������� ������");
 *  scan_data();
 *  tick_time("��������� ����");
 *  load_file();
 *  tick_time("����������� ����������");
 *  $analyzer->dataAnalyze();
 *  tick_time();
 *  ������ � ������� (��� � �������)
 *
 * @param string $message - �������� �������� ������� �� ��������. ������������ ������ ��� ���� ����� �����.
 */
function tick_time($message = '')
{
    static $lastMessage;
    static $startTime;
    if($startTime)
    {
        echo '\n ' . $lastMessage . ': ' . ((int)((microtime(true) - $startTime) * 10000)) / 10000 . '\n';
    }
    $startTime   = microtime(true);
    $lastMessage = $message;
}


/**
 * �������� ����� ���������� ������� ����
 *
 * @param $fn
 *
 * @return mixed
 *
 * echo bench(function(){ sleep(1); });
 * 1.0001661777496
 *
 */
function bench($fn)
{
    $start = microtime(true);
    $fn();

    return microtime(true) - $start;
}


/**
 *  ������� ������ print_r, �� ����������� ���� ��� ������� �� ��� �������
 *  ������� ��� �������, � ������ ���������, ��������� ������ � �������.
 *
 * @param array   $v ���������� ��� ������
 * @param integer $maxdepth ������������ �������,
 *     ���� $maxdepth < 0 - �� ������� ����� ��������������
 * @param integer $prepend_spaces ���������� �������� ����� �������
 *     � �������. ��� ������� ������������ �� �����, ����� �������� ����.
 *
 * @return string ���������� ������ (� ������� �� print_r �� ������ echo)
 */
function print_r_slice($v, $maxdepth = -1, $prepend_spaces = 0)
{
    $result = '';
    if(is_array($v) || is_object($v))
    {
        if($maxdepth != 0)
        {
            foreach($v as $key => $val)
            {
                $result .= '\n' . str_repeat(' ', $prepend_spaces) . ('[' . $key
                        . '] => ' . print_r_slice($val, $maxdepth - 1,
                                                  $prepend_spaces + strlen($key) + 6));
            }
        }
        else
        {
            $result .= ' Array() ';
        }
    }
    else
    {
        $result .= $v;
    }

    return $result;
}


/**
 * ���������������� ���������
 * echo fix_charset('Сведения о прошлой т� �удовой деятельности
 * (2 последних мест�');
 *
 * @param $string
 *
 * @return mixed
 */
function fix_charset($string)
{
    $chek_string  = '��������������������������������';  //  ������ ��� ������ ����������
    $list_charset = ['UTF-8', 'ASCII', 'Windows-1252', 'CP1256', 'CP1251']; // ������ ��������� ��� ������
    $array_result = [];

    foreach($list_charset as $current_charset)
    {
        foreach($list_charset as $two_charset)
        {
            $string_after_conversion = iconv($current_charset, $two_charset, $string);
            // �������� ���������� � ��������� ������ � ������
            $matches         = 0;
            $len_chek_string = strlen($chek_string);
            for($k = 0; $k < $len_chek_string; $k++)
            {
                if(eregi($chek_string[$k], $string_after_conversion))
                {
                    $matches++;
                }
            }
            $array_result[] = [
                'matches'     => $matches,
                'charset_in'  => $current_charset,
                'charset_out' => $two_charset,
                'result'      => $string_after_conversion,
            ];
            // ����� ��������
            //echo '<br>'.$current_charset.' - '.$two_charset." - ".$string_after_conversion." (".$matches."/".$len_chek_string.")";
        }
    }
//  ��� ����� ��� ���������� ������������ ������� �� ���� matches
    $t = call_user_func_array('array_merge_recursive', $array_result);
    asort($t['matches']);
    $so = array_keys($t['matches']);
    asort($so);
    $so           = array_keys($so);
    $array_result = array_combine($so, $array_result);
    krsort($array_result);
//  ������ ������ ������������ �� ������� ���� (����� ����������)....
    /*echo '<pre>';
    print_R($array_result);
    echo '</pre>'; die;*/

//  ��������� ��������� ������� �������, � ����� ������� ����������� ���������� ��������
    return $array_result[count($array_result) - 1]['result'];
}

/**
 * ������� ��������� ����������
 *
 * echo highlight_html('
 * <!-- This is an
 * HTML comment -->
 * <a href="home.html" style="color:blue;">Home</a>
 * <p>Go &amp; here.</p>
 * <!-- This is an HTML comment -->
 * <form action="/login.php" method="post">
 * <input type="text" value="User Name" />
 * </form>
 * ');
 *
 * @param           $string
 * @param bool|TRUE $decode
 *
 * @return string
 */
function highlight_html($string, $decode = true)
{
    $tag     = '#0000ff';
    $att     = '#ff0000';
    $val     = '#8000ff';
    $com     = '#34803a';
    $find    = [
        '~(\s[a-z].*?=)~',                    // Highlight the attributes
        '~(&lt;\!--.*?--&gt;)~s',            // Hightlight comments
        '~(&quot;[a-zA-Z0-9\/].*?&quot;)~',    // Highlight the values
        '~(&lt;[a-z].*?&gt;)~',                // Highlight the beginning of the opening tag
        '~(&lt;/[a-z].*?&gt;)~',            // Highlight the closing tag
        '~(&amp;.*?;)~',                    // Stylize HTML entities
    ];
    $replace = [
        '<span style="color:' . $att . ';">$1</span>',
        '<span style="color:' . $com . ';">$1</span>',
        '<span style="color:' . $val . ';">$1</span>',
        '<span style="color:' . $tag . ';">$1</span>',
        '<span style="color:' . $tag . ';">$1</span>',
        '<span style="font-style:italic;">$1</span>',
    ];
    if($decode)
    {
        $string = htmlentities($string);
    }

    return '<pre>' . preg_replace($find, $replace, $string) . '</pre>';
}

/**
 * ���������� ������������ �������������� ������� �� PHP �� ������ ����
 *
 * @param $array_result
 * @param $field
 *
 * @return array
 */
function sort_hard_array($array_result, $field)
{
    $t = call_user_func_array('array_merge_recursive', $array_result);
    asort($t[$field]);
    $so = array_keys($t[$field]);
    asort($so);
    $so           = array_keys($so);
    $array_result = array_combine($so, $array_result);
    krsort($array_result);

    return $array_result;
}

/**
 * ������� ���� �������� ��� ���������� ������
 *
 * @return mixed
 */
function array_concat()
{
    $args = func_get_args();
    foreach($args as $ak => $av)
    {
        $args[$ak] = array_values($av);
    }

    return call_user_func_array('array_merge', $args);
}