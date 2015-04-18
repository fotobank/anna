<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 08.07.14
 * Time: 4:36
 */


/**
 * Для обрезки строки на 2 части
 *
 * @param $string
 * @param $minlength
 * @param $maxlen
 *
 * @return mixed
 */
function cutString( $string, $minlength, $maxlen ) {

	$len         = ( mb_strlen( $string ) > $maxlen )
		? mb_strripos( mb_substr( $string, $minlength, $maxlen ), ' ' )
		: $maxlen;
	$cutStr      = mb_substr( $string, $minlength, $len );
	$cutStr_last = mb_substr( $string, $len, mb_strlen( $string ) );
	$str[0]      = $cutStr;
	$str[1]      = $cutStr_last;
	return $str;

}

/**
 * @param $path
 *
 * @return mixed
 */
function _include( $path ) {
	return include preg_replace( '/[/]+/', DIRECTORY_SEPARATOR, $path );
}

/**
 * @param $num
 * @param $p
 *
 * @return int|string
 */
function sklon( $num, $p ) {

	if ( !is_numeric( $num ) ) return $num;

	$numret = (int) $num;
	$num    = (int) abs( $num );

	$name = array(
		'y' => array( 'год', 'года', 'лет' ),
		'm' => array( 'месяц', 'месяца', 'месяцев' ),
		'd' => array( 'день', 'дня', 'дней' ),
		'h' => array( 'час', 'часа', 'часов' ),
		'i' => array( 'минута', 'минуты', 'минут' ),
		's' => array( 'секунда', 'секунды', 'секунд' )
	);

	if ( !array_key_exists( $p, $name ) ) return $num;

	$cases = [ 2, 0, 1, 1, 1, 2 ];
	$str   = $name[$p][( $num % 100 > 4 && $num % 100 < 20 ) ? 2 : $cases[min( $num % 10, 5 )]];

	return $numret . ' ' . $str;

}

/** Склонение существительных с числительными
 *
 * @param int    $n     число
 * @param string $form1 Единственная форма: 1 секунда
 * @param string $form2 Двойственная форма: 2 секунды
 * @param string $form5 Множественная форма: 5 секунд
 *
 * @return string Правильная форма
 *
 *  echo pluralForm($i, 'робот', 'робота', 'роботов');
 */
function pluralForm( $n, $form1, $form2, $form5 ) {
	$n  = abs( $n ) % 100;
	$n1 = $n % 10;
	if ( $n > 10 && $n < 20 ) return $form5;
	if ( $n1 > 1 && $n1 < 5 ) return $form2;
	if ( $n1 == 1 ) return $form1;
	return $form5;
}

/**
 *
 * sklonenie_slov($c, array('слово', 'слова', 'слов'));
 *
 * @param $chislo
 * @param $slova
 *
 * @return mixed
 */
function sklonenie_slov( $chislo, $slova ) {
	$keisi = array( 2, 0, 1, 1, 1, 2 );
	return $slova[( $chislo % 100 > 4 && $chislo % 100 < 20 ) ? 2 : $keisi[min( $chislo % 10, 5 )]];
}

/**
 * Обработка Post
 */
function post_var() {

	foreach ( func_get_args() as $key ) {
		if ( !empty( $_POST[$key] ) ) {
			$$key = $_POST[$key];
		} else {
			$$key = NULL;
		}
	}

}


/** ========================================================= */
/**
 * @param $password
 *
 * @return string
 */
function password_encrypt( $password ) {

	$hash_format     = "$2y$10$";
	$salt_length     = 22;
	$salt            = generate_salt( $salt_length );
	$format_and_salt = $hash_format . $salt;
	$hash            = crypt( $password, $format_and_salt );
	return $hash;
}

/**
 * @param $length
 *
 * @return string
 */
function generate_salt( $length ) {

	$unique_random_string   = md5( uniqid( mt_rand(), true ) );
	$base64_string          = base64_encode( $unique_random_string );
	$modified_base64_string = str_replace( "+", ".", $base64_string );
	$salt                   = substr( $modified_base64_string, 0, $length );
	return $salt;
}

/** ================================================================ */
/**
 * array glean
 * очистка от пучтых значений и 0
 * вызов : $array = array_filter ($array );
 */
/**
 * $ar = array_filter(
 * $ar,
 * function($el){ return !empty($el);}
 * );
 */

/**
 * очистка от пустых значений
 *
 * @param $array
 *
 * @return array
 */
function array_clean( $array ) {
	return array_diff( $array, [ null ] );
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
function file_newname( $path, $filename ) {
	$pos = strrpos( $filename, '.' );
	$ext = substr( $filename, $pos );
	if ( $pos ) {
		$name = substr( $filename, 0, $pos );
	} else {
		$name = $filename;
	}

	$newpath = $path . '/' . $filename;
	$newname = $filename;
	$counter = 0;
	while ( file_exists( $newpath ) ) {
		$newname = $name . '_' . $counter . $ext;
		$newpath = $path . '/' . $newname;
		$counter ++;
	}

	return $newname;
}

/**
 * @return string
 */
function Greeting() {
	$hour = date( "H" );
	if ( $hour < 6 )
		return "Доброй ночи";
	if ( $hour < 12 )
		return "Доброе утро";
	if ( $hour < 18 )
		return "Добрый день";
	if ( $hour <= 23 )
		return "Добрый вечер";
	return "Доброй ночи";
}

/**
 * ссылка на картинку сразу отобразиться картинка, на Youtube сразу роликом, а остальные просто ссылкой
 *
 * @param $text
 *
 * @return mixed
 */
function ssilka( $text ) {
	$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
	if ( preg_match_all( $reg_exUrl, $text, $output ) ) {
		foreach ( $output[0] as $url ) {
			$youtubePattern = '#(http://www.youtube.com)?/(v/([-|~_0-9A-Za-z]+)|watch\?v\=([-|~_0-9A-Za-z]+)&*(\/\S*)?)#i';
			if ( preg_match( $youtubePattern, $url, $youtubemathes ) ) {
				$video_id = $youtubemathes[4];
				$embed    = '<br /><object width="480" height="390"><param name="movie" value="http://www.youtube.com/v/' . $video_id . '&hl=en&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $video_id . '&hl=en&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="390"></embed></object>';
				$text     = str_replace( $url, $embed, $text );
			} else {
				$aHeader = get_headers( $url, 1 );
				if ( array_key_exists( 'Content-Type', $aHeader ) && substr( $aHeader['Content-Type'], 0, 6 ) == 'image/' ) {
					$img  = '<br /><a href="' . $url . '" rel="nofollow"><img src="' . $url . '" /></a>';
					$text = str_replace( $url, $img, $text );
				} else {
					$link = '<a href="' . $url . '" rel="nofollow">' . $url . '</a>';
					$text = str_replace( $url, $link, $text );
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
function hideEmail( $email ) {
	$character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
	$key           = str_shuffle( $character_set );
	$cipher_text   = '';
	$id            = 'e' . rand( 1, 999999999 );
	for ( $i = 0; $i < strlen( $email ); $i += 1 ) {
		$cipher_text .= $key[strpos( $character_set, $email[$i] )];
	}
	$script = 'var a="' . $key . '";var b=a.split("").sort().join("");var c="' . $cipher_text . '";var d="";';
	$script .= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
	$script .= 'document.getElementById("' . $id . '").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
	$script = "eval(\"" . str_replace( [ "\\", '"' ], [ "\\\\", '\"' ], $script ) . "\")";
	$script = '<script type="text/javascript">/*<![CDATA[*/' . $script . '/*]]>*/</script>';
	return '<span id="' . $id . '">[javascript protected email address]</span>' . $script;
}

/**
 * Отправка e-mail в русской кодировке средствами PHP:
 *
 * send_mime_mail('Автор письма',
 *                            'sender@site.ru',
 *                            'Получатель письма',
 *                            'recepient@site.ru',
 *                            'CP1251',  // кодировка, в которой находятся передаваемые строки
 *                            'KOI8-R', // кодировка, в которой будет отправлено письмо
 *                            'Письмо-уведомление',
 *                           "Здравствуйте, я Ваша программа!"
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
function send_mime_mail( $name_from, // имя отправителя
						 $email_from, // email отправителя
						 $name_to, // имя получателя
						 $email_to, // email получателя
						 $data_charset, // кодировка переданных данных
						 $send_charset, // кодировка письма
						 $subject, // тема письма
						 $body, // текст письма
						 $html = FALSE, // письмо в виде html или обычного текста
						 $reply_to = FALSE
) {
	$to      = mime_header_encode( $name_to, $data_charset, $send_charset )
		. ' <' . $email_to . '>';
	$subject = mime_header_encode( $subject, $data_charset, $send_charset );
	$from    = mime_header_encode( $name_from, $data_charset, $send_charset )
		. ' <' . $email_from . '>';
	if ( $data_charset != $send_charset ) {
		$body = iconv( $data_charset, $send_charset, $body );
	}
	$headers = "From: $from\r\n";
	$type    = ( $html ) ? 'html' : 'plain';
	$headers .= "Content-type: text/$type; charset=$send_charset\r\n";
	$headers .= "Mime-Version: 1.0\r\n";
	if ( $reply_to ) {
		$headers .= "Reply-To: $reply_to";
	}
	return mail( $to, $subject, $body, $headers );
}

/**
 * вспомогательная функция:
 *
 * @param $str
 * @param $data_charset
 * @param $send_charset
 *
 * @return string
 */
function mime_header_encode( $str, $data_charset, $send_charset ) {
	if ( $data_charset != $send_charset ) {
		$str = iconv( $data_charset, $send_charset, $str );
	}
	return '=?' . $send_charset . '?B?' . base64_encode( $str ) . '?=';
}


/**
 * @param string $year
 *
 * @return bool|int|string
 */
function auto_copyright( $year = 'auto' ) {
	if ( intval( $year ) == 'auto' ) {
		$year = date( 'Y' );
	}
	if ( intval( $year ) == date( 'Y' ) ) {
		return intval( $year );
	}
	if ( intval( $year ) < date( 'Y' ) ) {
		return intval( $year ) . ' - ' . date( 'Y' );
	}
	if ( intval( $year ) > date( 'Y' ) ) {
		return date( 'Y' );
	}
}

/**
 * @param string $file   Filepath
 * @param string $format dateformat
 *
 * @link http://www.php.net/manual/de/function.date.php
 * @link http://www.php.net/manual/de/function.filemtime.php
 * @return string|bool Date or Boolean
 */
function getFiledate( $file, $format ) {
	if ( is_file( $file ) ) {
		$filePath = $file;
		if ( !realpath( $filePath ) ) {
			$filePath = $_SERVER["DOCUMENT_ROOT"] . $filePath;
		}
		$fileDate = filemtime( $filePath );
		if ( $fileDate ) {
			$fileDate = date( "$format", $fileDate );
			return $fileDate;
		}
		return false;
	}
	return false;
}

/**
 * @param $file
 * расширение файла
 *
 * @return mixed
 */
function getFileextension( $file ) {
	return pathinfo( $file, PATHINFO_EXTENSION );
}

/**
 * @param $file
 * расширение файла
 *
 * @return mixed
 */
function get_Fileextension( $file ) {
	return end( explode( ".", $file ) );
}

/**
 * @param string $file  Filepath
 * @param string $query Needed information (0 = width, 1 = height, 2 = mime-type)
 *
 * @return string Fileinfo
 */
function getImageinfo( $file, $query ) {
	if ( !realpath( $file ) ) {
		$file = $_SERVER["DOCUMENT_ROOT"] . $file;
	}
	$image = getimagesize( $file );
	return $image[$query];
}

/**
 * @return object
 */
function db() {
	return Mysqli_Db::getInstance( Mysqli_Db::get_param() );
}

/**
 * добавление кодировки в функцию htmlspecialchars
 *
 * @param        $str
 * @param        $quote
 * @param string $encoding
 *
 * @return string
 */
function my_htmlspecialchars( $str, $quote, $encoding = "cp1251" ) {
	return htmlspecialchars( $str, $quote, $encoding );
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
function sterilize( $input, $is_sql = false ) {
	$input = htmlentities( $input, ENT_QUOTES );

	if ( get_magic_quotes_gpc() ) {
		$input = stripslashes( $input );
	}

	if ( $is_sql ) {
		$input = mysql_real_escape_string( $input );
	}

	$input = strip_tags( $input );
	$input = str_replace( "  ", "\n", $input );

	return $input;
}

/**
 * @param string $addr
 * @param string $code
 */
function main_redir( $addr = '', $code = '303' ) {
	if ( empty( $addr ) ) {
		if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
			header( "location: " . $_SERVER['HTTP_REFERER'], true, $code );
		} else {
			header( 'location: /index.php', true, $code );
		}
	} else {
		header( 'location: ' . $addr, true, $code );
	}

	exit();
}

/**
 *
 */
function admin_only() {
	if ( !isset( $_SESSION['logged'] ) ) {
		echo ' < div class="title2" > Извините ,данная функция доступна только для администратора < br /><a href = "index.php" > Админка</a ></div > ';
	}
}

/**
 * @param $str
 *
 * @return bool
 */
function if_admin( $str ) {
	if ( isset( $_SESSION['logged'] ) && $_SESSION['logged'] == TRUE ) {
		if ( isset( $_SESSION['admnews'] ) && $_SESSION['admnews'] == md5( login() . '///' . pass() ) ) ;
		{
			return $str;
		}
	}
	return false;
}

/**
 * @return mixed
 */
function login() {
	db()->where( "id", 1 );
	$login = db()->getOne( $GLOBALS['tbl_users'], 'login' );
	return $login['login'];
}


/**
 * @return mixed
 */
function pass() {
	db()->where( "id", 1 );
	$login = db()->getOne( $GLOBALS['tbl_users'], 'pass' );
	return $login['pass'];
}

/**
 * @param $array
 */
function pre( $array ) {
	echo '<pre style="background-color: #cccccc; color: #010C01;" > ';
	print_r( $array );
	echo "</pre>";
}

/**
 * @return array|bool
 */
function getConfig() {
	$config = [ ];
	if ( is_readable( __DIR__ . "/passwords.php" ) ) {
		$handle = file( __DIR__ . "/passwords.php", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
		array_shift( $handle );
		if ( $handle ) {
			foreach ( $handle as $data ) {
				$rec = explode( "=>", $data );
				if ( count( $rec ) === 2 ) {
					$config[trim( $rec[0] )] = trim( $rec[1] );
				}
			}
		} else {
			trigger_error( "Подключение невозможно - не удалось открыть файл!", E_USER_ERROR );
			return false;
		}
	} else {
		trigger_error( "Подключение невозможно - не найден файл с паролем!", E_USER_ERROR );
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
 *  echo _strtr('Мобильный телефон Samsung обменяю на новый iphone',array(
 * 'Мобильный телефон'=>'Мобильник',
 * 'samsung'=>'Самсунг', 'новый'=>'новенький','обменяю'=>'поменяю',
 * 'Iphone'=>'Айфон'
 * )
 *                                                           );
 */
function _strtr( $str, $repl_array ) {
	$keys   = array_map( function ( $key ) {
		return '#' . $key . '#i';
	}, array_keys( $repl_array ) );
	$values = array_values( $repl_array );

	return preg_replace( $keys, $values, $str );
}

/** Remove white space, comments etc.*/
function clean_file( $content ) {

	$content = preg_replace( '/^\s+|\n|\r|\s+$/m', '', $content );
	$content = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content );
	$content = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $content );
	$content = preg_replace( array( '(( )+{)', '({( )+)' ), '{', $content );
	$content = preg_replace( array( '(( )+})', '(}( )+)', '(;( )*})' ), '}', $content );
	$content = preg_replace( array( '(;( )+)', '(( )+;)' ), ';', $content );
	$content = preg_replace( array( '(:( )+)', '(( )+:)' ), ':', $content );
	$content = preg_replace( '/(\s|)\,(\s|)/', ',', $content );

	return $content;

}

function cleanInput( $input ) {
	$search = array(
		'@<script[^>]*?>.*?</script>@si', // javascript
		'@<[\/\!]*?[^<>]*?>@si', // HTML теги
		'@<style[^>]*?>.*?</style>@siU', // теги style
		'@<![\s\S]*?--[ \t\n\r]*>@' // многоуровневые комментарии
	);
	$output = preg_replace( $search, '', $input );
	return $output;
}

// $text = preg_replace('/(\r\n){2,}/i', '\r\n\r\n', $text); заменить двойный переводы строк
// $value = preg_replace('/\s{2,}/', '', $value); убрать лишние пробелы


/**
 * @param $data
 *
 * @return mixed
 */
function sql_valid( $data ) {
	$data = str_replace( "\\", "\\\\", $data );
	$data = str_replace( "'", "\'", $data );
	$data = str_replace( '"', '\"', $data );
	$data = str_replace( "\x00", "\\x00", $data );
	$data = str_replace( "\x1a", "\\x1a", $data );
	$data = str_replace( "\r", "\\r", $data );
	$data = str_replace( "\n", "\\n", $data );
	return ( $data );
}

/**
 * для базы данных
 *
 * @param $input
 *
 * @return bool|mixed
 */
function sanitize( $input ) {

	if ( is_array( $input ) ) {
		foreach ( $input as $var => $val ) {
			$output[$var] = sanitize( $val );
		}
	} else {
		if ( get_magic_quotes_gpc() ) {
			$input = stripslashes( $input );
		}
		$input  = cleanInput( $input );
		$output = sql_valid( $input );

	}
	return isset( $output ) ? $output : false;
}

/**
 * @param $str
 *
 * @return string
 */
function cp1251_utf8( $str ) {

	if ( mb_check_encoding( $str, 'Windows-1251' ) && !mb_check_encoding( $str, 'UTF-8' ) ) {
		$str = mb_convert_encoding( $str, 'UTF-8', 'Windows-1251' ); // из Windows-1251 в UTF-8
	}
	return $str;
}

/**
 * @param $str
 *
 * @return string
 */
function utf8_cp1251( $str ) {

	if ( !mb_check_encoding( $str, 'Windows-1251' ) && mb_check_encoding( $str, 'UTF-8' ) ) {
		$str = mb_convert_encoding( $str, 'Windows-1251', 'UTF-8' ); // из UTF-8 в Windows-1251
	}
	return $str;
}

/**
 * @param $string
 *
 * @return string
 */
function utf8( $string ) {
	return iconv( 'cp1251', 'utf-8', $string );
}

/**
 * @param $string
 *
 * @return string
 */
function cp1251( $string ) {
	return iconv( 'utf-8', 'cp1251//IGNORE', $string );
}


/**
 * @param $str
 * @param $type
 *
 * @return mixed
 */
function WinUtf( $str, $type ) // $type: 'w' - encodes from UTF to win 'u' - encodes from win to UTF
{
	static $conv = '';
	if ( !is_array( $conv ) ) {
		$conv = [ ];
		for ( $x = 128; $x <= 143; $x ++ ) {
			$conv['utf'][] = chr( 209 ) . chr( $x );
			$conv['win'][] = chr( $x + 112 );
		}
		for ( $x = 144; $x <= 191; $x ++ ) {
			$conv['utf'][] = chr( 208 ) . chr( $x );
			$conv['win'][] = chr( $x + 48 );
		}
		$conv['utf'][] = chr( 208 ) . chr( 129 );
		$conv['win'][] = chr( 168 );
		$conv['utf'][] = chr( 209 ) . chr( 145 );
		$conv['win'][] = chr( 184 );
	}
	if ( $type == 'w' )
		return str_replace( $conv['utf'], $conv['win'], $str );
	elseif ( $type == 'u' )
		return str_replace( $conv['win'], $conv['utf'], $str );
	else
		return $str;
}

/**
 *
 * @param $path
 *
 * @return array|bool
 * array [ dirname, basename, extension, filename ]
 * с поддержкоц кодировки UTF-8
 */
function path_info( $path ) {

	if ( strpos( $path, '/' ) !== false ) {
		$basename = explode( '/', $path );
		$basename = end( $basename );
	} else if ( strpos( $path, '\\' ) !== false ) {
		$basename = end( explode( '\\', $path ) );
	} else {
		return false;
	}
	if ( !$basename )
		return false;

	$dirname = substr( $path, 0,
		strlen( $path ) - strlen( $basename ) - 1 );

	if ( strpos( $basename, '.' ) !== false ) {
		$extension = explode( '.', $path );
		$extension = end( $extension );
		$filename  = substr( $basename, 0,
			strlen( $basename ) - strlen( $extension ) - 1 );
	} else {
		$extension = '';
		$filename  = $basename;
	}

	return [
		'dirname'   => $dirname,
		'basename'  => $basename,
		'extension' => $extension,
		'filename'  => $filename
	];
}


/**
 *
 *  basename для сервера с utf-8 поддерживающая кодировку Windows-1251
 *
 * @param      $param
 *
 * @param null $suffix
 *
 * @return mixed|string
 */
function __basename( $param, $suffix = null ) {

	$basename = strrpos( $param, DIRECTORY_SEPARATOR );
	$basename = substr( $param, $basename );
	$basename = ltrim( $basename, DIRECTORY_SEPARATOR );

	if ( $suffix ) {

		if ( ( strpos( $param, $suffix ) + strlen( $suffix ) ) == strlen( $param ) ) {
			return str_ireplace( $suffix, '', $basename );
		} else {
			return $basename;
		}
	} else {
		return $basename;
	}
}

/**
 * @param $path
 *
 * @return mixed|null
 */
function basename_utf8( $path ) {

	if ( strpos( $path, '/' ) !== false ) return end( explode( '/', $path ) );
	elseif ( strpos( $path, '\\' ) !== false ) return end( explode( '\\', $path ) );
	else return null;
}

/**
 * Функция детектит кодировку
 *
 * @param $string
 *
 * @return string $string
 */
function detect_encoding( $string ) {
	static $list = [ 'utf-8', 'windows-1251' ];

	foreach ( $list as $item ) {
		$sample = @iconv( $item, ($item.'//IGNORE'), $string );
		if ( md5( $sample ) == md5( $string ) )
			return $item;
	}
	return 'файловая система не опознана';
}

/**
 *  универсальный basename
 *
 * @param $path
 *
 * @return string
 */
function _basename( $path ) {

	if ( detect_encoding( $path ) == 'windows-1251' ) {
		return basename( $path );
	} else {
		return ltrim( substr( $path, strrpos( $path, DIRECTORY_SEPARATOR ) ), DIRECTORY_SEPARATOR );
	}
}

/**
 * преобразование массива в одномерный
 *
 * @param $multiarray
 *
 * @return array
 */
function flat_array( $multiarray ) {
	$result   = [ ];
	$iterator = new RecursiveIteratorIterator( new RecursiveArrayIterator( $multiarray ) );

	foreach ( $iterator as $value ) {
		$result[] = $value; // тут возвращаете как вам хочется
	}

	return $result;
}

/**
 * поиск во вложенных заданных поддиректориях, если они не заданны - то во всех подпапках
 * $dir = 'files/portfolio/';
 * $ok_subdir = array( 'thumb' ); // в каких поддиректориях искать файлы
 * $mask = '*.jpg'; // маска поиска
 * $thumb = recursive_dir( $dir, $mask, $ok_subdir );
 *
 * @param        $dir         - папка поиска
 * @param string $mask        - маска дозволенных папок
 * @param array  $no_subdir   - какие папки вывода исключить
 * @param bool   $multi_arrau - переключатель вывода в одномерный массив
 * @param array  $ok_subdir   - в каких субдирректориях искать
 *
 * @return array
 */
function recursive_dir( $dir, $mask = '.jpg', $ok_subdir = [ ], $no_subdir = [ ], $multi_arrau = true ) {
	static $arr = [ ];
	$cont = glob( $dir . "/*" );
	if ( count( $cont ) ) {
		$name_subdir = basename( $dir );
		foreach ( $cont as $file ) {
			if ( in_array( $name_subdir, $ok_subdir ) || !count( $ok_subdir ) ) {
				if ( !in_array( $name_subdir, $no_subdir ) ) {
					if ( is_dir( $file ) ) {
						recursive_dir( $file, $mask, $ok_subdir, $no_subdir, $multi_arrau );
					} else {
						if ( strpos( $file, $mask ) !== false ) {
							if ( $multi_arrau ) {
								$arr[$name_subdir][] = $file;
							} else {
								$name_dir = explode( "/", $file );
								$arr[]    = [ $name_dir[2], $file ];
							}
						}
					}
				}
			} elseif ( is_dir( $file ) ) {
				$cont_subdir = glob( $file . "/*", GLOB_ONLYDIR );
				if ( count( $cont_subdir ) ) {
					foreach ( $cont_subdir as $file2 ) {
						recursive_dir( $file2, $mask, $ok_subdir, $no_subdir, $multi_arrau );
					}
				}
			}
		}
	}
	return $arr;
}


/**
 * поиск во вложенных заданных поддиректориях, если они не заданны - то во всех подпапках
 * $dir = 'files/portfolio/';
 * $ok_subdir = array( 'thumb' ); // в каких поддиректориях искать файлы
 * $mask = '*.jpg'; // маска поиска
 * $thumb = recursive_dir( $dir, $mask, $ok_subdir );
 *
 * @param        $dir         - папка поиска
 * @param string $mask        - маска дозволенных папок
 * @param bool   $multi_arrau - переключатель вывода в одномерный массив
 * @param array  $ok_subdir   - в каких субдирректориях искать
 *
 * @return array
 */
function recursive_dir2( $dir, $mask = '*', $ok_subdir = [ ], $multi_arrau = true ) {
	static $thumb = [ ];
	$skan = glob( $dir . $mask );
	if ( in_array( basename( $dir ), $ok_subdir ) ) {
		if ( $skan ) $thumb[] = $skan;
	} elseif ( empty( $ok_subdir ) ) {
		if ( $skan ) $thumb[] = $skan;
	}
	foreach ( glob( $dir . '*', GLOB_ONLYDIR ) as $subdir ) {
		if ( $subdir ) {
			$skan = glob( $subdir . '/' . $mask );
			if ( in_array( basename( $subdir ), $ok_subdir ) ) {
				if ( $skan ) $thumb[] = $skan;
			} elseif ( empty( $ok_subdir ) ) {
				if ( $skan ) $thumb[] = $skan;
			}
			$subsubdir = glob( $subdir . '/*', GLOB_ONLYDIR );
			if ( count( $subsubdir ) ) {
				foreach ( $subsubdir as $subdir2 ) {
					recursive_dir2( $subdir2 . '/', $mask, $ok_subdir );
				}
			}
		}
	}
	if ( $multi_arrau ) {
		return $thumb;
	} else {
		$result = [ ];
		array_walk_recursive( $thumb, function ( $value ) use ( &$result ) {
			$result[] = $value; // тут возвращаете как вам хочется
		} );
		return $result;
	}

}


/**
 * @param     $input
 * @param int $i
 *
 * @return array
 * случайный выбор элемента массива с заданным количеством элементов
 */
function my_array_rand( $input, $i = 2 ) {
	if ( $i > count( $input ) ) $i = count( $input );
	srand( (float) microtime() * 10000000 );
	$rand_keys = array_rand( $input, $i );
	$res       = [ ];
	if ( $i > 1 ) {
		for ( $a = 0; $a < $i; $a ++ ) {
			$res[] = $input[$rand_keys[$a]];
		}
	} else {
		$res[] = $input[$rand_keys];
	}
	return $res;
}

/**
 * @param     $array
 * @param int $limit
 *
 * @return array
 * случайный выбор элемента массива с заданным количеством элементов
 */
function get_random_elements( $array, $limit = 0 ) {

	if ( $array ) {
		shuffle( $array );
		if ( $limit > 0 ) {
			$array = array_splice( $array, 0, $limit );
		}
	} else {
		return false;
	}
	return $array;
}

/**
 * @param $folder
 * @param $space
 * вывод дерева каталога
 * Запускаем функцию для каталога /files:
 * showTree("./files", "");
 */
function showTree( $folder, $space ) {
	/* Получаем полный список файлов и каталогов внутри $folder */
	$files = scandir( $folder );
	foreach ( $files as $file ) {
		/* Отбрасываем текущий и родительский каталог */
		if ( ( $file == '.' ) || ( $file == '..' ) ) continue;
		$f0 = $folder . '/' . $file; //Получаем полный путь к файлу
		/* Если это директория */
		if ( is_dir( $f0 ) ) {
			/* Выводим, делая заданный отступ, название директории */
			echo $space . $file . "<br />";
			/* С помощью рекурсии выводим содержимое полученной директории */
			showTree( $f0, $space . '&nbsp;&nbsp;&nbsp;&nbsp;' );
		} /* Если это файл, то просто выводим название файла */
		else echo $space . $file . "<br />";
	}
}

function replaceBBCode( $text_post ) {
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
		"#\[\*\](.+?)\[\/\*\]#"
	];
	$str_replace = [
		"",
		"<p class=\"komment\">\\1</p>",
		"<span class=\"date\">\\1</span>",
//        "<br />",
		"<b>\\1</b>",
		"<i>\\1</i>",
		"<span style='text-decoration:underline'>\\1</span>",
		"<code class='code'>\\1</code>",
		"<table width = '95%'><tr><td>Цитата</td></tr><tr><td class='quote'>\\1</td></tr></table>",
		"<a href='\\1'>\\2</a>",
		"<a href='\\1'>\\1</a>",
		"<img src='\\1' alt = 'Изображение' />",
		"<span style='font-size:\\1%'>\\2</span>",
		"<span style='color:\\1'>\\2</span>",
		"<ul>\\1</ul>",
		"<ol>\\1</ol>",
		"<li>\\1</li>"
	];
	return preg_replace( $str_search, $str_replace, $text_post );
}

/**
 * @return string
 */
function ip() {
	if ( $_SERVER ) {
		if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && $_SERVER['HTTP_X_FORWARDED_FOR'] )
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		elseif ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && $_SERVER['HTTP_CLIENT_IP'] )
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		else
			$ip = $_SERVER['REMOTE_ADDR'];
	} else {
		if ( getenv( 'HTTP_X_FORWARDED_FOR' ) )
			$ip = getenv( 'HTTP_X_FORWARDED_FOR' );
		elseif ( getenv( 'HTTP_CLIENT_IP' ) )
			$ip = getenv( 'HTTP_CLIENT_IP' );
		else
			$ip = getenv( 'REMOTE_ADDR' );
	}

	return $ip;
}

/**
 * @param $myIP
 *
 * @return string
 */
function detect_proxy( $myIP ) {
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
		'HTTP_PROXY_CONNECTION'
	];

	$flagProxy = false;
	$libProxy  = 'No';

	foreach ( $scan_headers as $i ) {
		if ( $_SERVER[$i] ) $flagProxy = true;
	}

	if ( in_array( $_SERVER['REMOTE_PORT'], array( 8080, 80, 6588, 8000, 3128, 553, 554 ) )
		|| @fsockopen( $_SERVER['REMOTE_ADDR'], 80, $errno, $errstr, 30 )
	)
		$flagProxy = true;

	// Proxy LookUp
	if ( $flagProxy == true &&
		isset( $_SERVER['REMOTE_ADDR'] ) &&
		!empty( $_SERVER['REMOTE_ADDR'] )
	)
		// Transparent Proxy
		// REMOTE_ADDR = proxy IP
		// HTTP_X_FORWARDED_FOR = your IP
		if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) &&
			!empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) &&
			$_SERVER['HTTP_X_FORWARDED_FOR'] == $myIP
		)
			$libProxy = 'Transparent Proxy';
		// Simple Anonymous Proxy
		// REMOTE_ADDR = proxy IP
		// HTTP_X_FORWARDED_FOR = proxy IP
		else if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) &&
			!empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) &&
			$_SERVER['HTTP_X_FORWARDED_FOR'] == $_SERVER['REMOTE_ADDR']
		)
			$libProxy = 'Simple Anonymous (Transparent) Proxy';
		// Distorting Anonymous Proxy
		// REMOTE_ADDR = proxy IP
		// HTTP_X_FORWARDED_FOR = random IP address
		else if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) &&
			!empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) &&
			$_SERVER['HTTP_X_FORWARDED_FOR'] != $_SERVER['REMOTE_ADDR']
		)
			$libProxy = 'Distorting Anonymous (Transparent) Proxy';
		// Anonymous Proxy
		// HTTP_X_FORWARDED_FOR = not determined
		// HTTP_CLIENT_IP = not determined
		// HTTP_VIA = determined
		else if ( $_SERVER['HTTP_X_FORWARDED_FOR'] == '' &&
			$_SERVER['HTTP_CLIENT_IP'] == '' &&
			!empty( $_SERVER['HTTP_VIA'] )
		)
			$libProxy = 'Anonymous Proxy';
		// High Anonymous Proxy
		// REMOTE_ADDR = proxy IP
		// HTTP_X_FORWARDED_FOR = not determined
		else
			$libProxy = 'High Anonymous Proxy';

	return $libProxy;
}

/**
 * Ресайз фото по большей стороне
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
function resize( $newWidth, $targetFile, $originalFile ) {
	$info = getimagesize( $originalFile );
	$mime = $info['mime'];
	switch ( $mime ) {
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
			throw Exception( 'Unknown image type.' );
	}
	$img = $image_create_func( $originalFile );
	list( $width, $height ) = getimagesize( $originalFile );
	if ( ( !$width ) || ( !$height ) ) {
		$GLOBALS['errors'][] = 'Image couldn\'t be resized because it wasn\'t a valid image.';
		return false;
	}
	if ( ( $width <= $newWidth ) && ( $height <= $newWidth ) ) {
		return false;
	} //no resizing needed
	//try max width first...
	$ratio = $newWidth / $width;
	$new_w = $newWidth;
	$new_h = $height * $ratio;
	//if that didn't work
	if ( $new_h > $newWidth ) {
		$ratio = $newWidth / $height;
		$new_h = $newWidth;
		$new_w = $width * $ratio;
	}
	$tmp = imagecreatetruecolor( $new_w, $new_h );
	imagecopyresampled( $tmp, $img, 0, 0, 0, 0, $new_w, $new_h, $width, $height );
	if ( file_exists( $targetFile ) ) {
		unlink( $targetFile );
	}
	$image_save_func( $tmp, "$targetFile.$new_image_ext" );
	return true;
}


/**
 * шифровка  пароля с солью
 *
 * $salt="123!#&%asgfHTA";
 * $pass="proba";
 *
 * @param $pass
 * @param $salt
 *
 * @return string
 */
function my_md5( $pass, $salt ) {
	$spec    = array( '~', '!', '@', '#', '$', '%', '^', '&', '*', '?' );
	$crypted = md5( md5( $salt ) . md5( $pass ) );
	$c_text  = md5( $pass );
	$temp    = '';
	for ( $i = 0; $i < strlen( $crypted ); $i ++ ) {
		if ( ord( $c_text[$i] ) >= 48 and ord( $c_text[$i] ) <= 57 ) {
			@$temp .= $spec[$c_text[$i]];
		} elseif ( ord( $c_text[$i] ) >= 97 and ord( $c_text[$i] ) <= 100 ) {
			@$temp .= strtoupper( $crypted[$i] );
		} else {
			@$temp .= $crypted[$i];
		}
	}
	return md5( $temp );
}

/**
 * Функция удаления файлов из директории через определенное время их хранения
 *
 * @param $papka
 * @param $times
 */
function old( $papka, $times ) {
	$old_time = time() - 60 * $times;
	$dir      = opendir( $papka );
	$files    = [ ];
	$time     = [ ];
	while ( $file = readdir( $dir ) ) {
		if ( ( $file != "." ) && ( $file != ".." ) )
			$files[] = "$papka/$file";
		$time[] = filemtime( "$papka/$file" );
	}
	closedir( $dir );
	$count_files = count( $files );
	for ( $i = 1; $i < $count_files; $i ++ ) {
		if ( $time[$i] <= $old_time ) {
			@unlink( $files[$i] );
		}
	}
}

/**
 * Функция вырезания всех нечитаемых символов
 *
 * @param      $text
 * @param null $br
 *
 * @return mixed
 */
function EscText( $text, $br = null ) // Вырезает все нечитаемые символы
{
	if ( $br != null )
		for ( $i = 0; $i <= 31; $i ++ ) {
			$text = str_replace( chr( $i ), null, $text );
		}
	else {
		for ( $i = 0; $i < 10; $i ++ ) {
			$text = str_replace( chr( $i ), null, $text );
		}
		for ( $i = 11; $i < 20; $i ++ ) {
			$text = str_replace( chr( $i ), null, $text );
		}
		for ( $i = 21; $i <= 31; $i ++ ) {
			$text = str_replace( chr( $i ), null, $text );
		}
	}
	return $text;
}

/**
 * Функция определения времени
 *
 * @param null $time
 *
 * @return bool|mixed|string
 */
function VrTime( $time = null ) {
	if ( $time == null )
		$time = time();
	$timep     = "" . date( "j M Y в H:i", $time ) . "";
	$time_p[0] = date( "j n Y", $time );
	$time_p[1] = date( "H:i", $time );
	if ( $time_p[0] == date( "j n Y" ) )
		$timep = "Сегодня в " . date( "H:i:s", $time );
	if ( $time_p[0] == date( "j n Y", time() - 60 * 60 * 24 ) )
		$timep = "Вчера в $time_p[1]";

	$timep = str_replace( "Jan", "Янв", $timep );
	$timep = str_replace( "Feb", "Фев", $timep );
	$timep = str_replace( "Mar", "Мар", $timep );
	$timep = str_replace( "May", "Мая", $timep );
	$timep = str_replace( "Apr", "Апр", $timep );
	$timep = str_replace( "Jun", "Июн", $timep );
	$timep = str_replace( "Jul", "Июл", $timep );
	$timep = str_replace( "Aug", "Авг", $timep );
	$timep = str_replace( "Sep", "Сен", $timep );
	$timep = str_replace( "Oct", "Окт", $timep );
	$timep = str_replace( "Nov", "Ноя", $timep );
	$timep = str_replace( "Dec", "Дек", $timep );
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
function UploadFTP( $ftpLogin, $ftpPass, $ftpAddr, $ftpPath, $ftpFile ) {
	$remoteurl = "ftp://${ftpLogin}:${ftpPass}@${ftpAddr}${ftpPath}/${ftpFile}";
	$ch        = curl_init();
	$fp        = fopen( $ftpFile, "rb" );
	curl_setopt( $ch, CURLOPT_URL, $remoteurl );
	curl_setopt( $ch, CURLOPT_UPLOAD, 1 );
	curl_setopt( $ch, CURLOPT_INFILE, $fp );
	curl_setopt( $ch, CURLOPT_INFILESIZE, filesize( $ftpFile ) );
	$error = curl_exec( $ch );
	curl_close( $ch );
	return $error;
}


/**
 *
 * echo cp1251(translate("Hello world!"));
 *
 * Для перевода с русского на английский, в параметрах hl, sl, tl нужно поменять ru на en и наоборот.
 * @param $_str
 *
 * @return mixed
 */
function translate($_str) {
	$curlHandle = curl_init();
	// options
	$postData= [ ];
	$postData['client']= 't';
	$postData['text']= $_str;
	$postData['hl'] = 'ru';
	$postData['sl'] = 'en';
	$postData['tl'] = 'ru';
	curl_setopt($curlHandle, CURLOPT_URL, 'http://translate.google.com/translate_a/t');
	curl_setopt($curlHandle, CURLOPT_HTTPHEADER, [
		'User-Agent: Mozilla/5.0 (X11; U; Linux i686; ru; rv:1.9.1.4) Gecko/20091016 Firefox/3.5.4',
		'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
		'Accept-Language: ru,en-us;q=0.7,en;q=0.3',
		'Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7',
		'Keep-Alive: 300',
		'Connection: keep-alive'
	] );
	curl_setopt($curlHandle, CURLOPT_HEADER, 0);
	curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curlHandle, CURLOPT_TIMEOUT, 10);
	curl_setopt($curlHandle, CURLOPT_POST, 0);
	if ( $postData!==false ) {
		curl_setopt($curlHandle, CURLOPT_POSTFIELDS, http_build_query($postData));
	}

	$content = curl_exec($curlHandle);
	curl_close($curlHandle);
	$content = str_replace(',,',',"",',$content);
	$content = str_replace(',,',',"",',$content);
	$result = json_decode($content);
	return $result[0][0][0];
}