<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 07.07.14
 * Time: 11:24
 */


use proxy\Db as Db;

require(__DIR__ .'/system/config/config.php'); // старт сессии, автолоадер, подключение базы, обработчик ошибок, файл функций
include_once( __DIR__ . '/inc/head.php' );

?>
	<!--==============================content================================-->
<?
$perpage = 10; //сообщений на страницу

$tpl = new Comments\Template( "classes/Comments/_templates/" );
$tpl->define( [
	"head"     => "head.tpl",
	"error"    => "error.tpl",
	"form"     => "form.tpl",
	"mess"     => "mess.tpl",
	"rep"      => "rep.tpl",
	"adm_opts" => "adm_opts.tpl",
	"foot"     => "foot.tpl",
	"sent"     => "sent.tpl",
	"foto_c"   => "foto_comm.tpl"
] );
$begin = $tpl->utime();
$tpl->assign( "{ADM_MENU}", '' );
$tpl->assign( "{SCRIPT}", $_SERVER['PHP_SELF'] );

IF ( !isset( $_POST['submit'] ) ) {
	$tpl->assign( "{REFRESH_LINK}", "<a class='link-3' href='" . $_SERVER['PHP_SELF'] . "'>reset</a>" );
	if ( isset( $_SESSION['logged'] ) && $_SESSION['logged'] == true ) {
		$tpl->assign( "{ADMIN_LINK}", "| <a class='link-3' href='/admin.php'>admin section</a>" );
	} else {
		$tpl->assign( "{ADMIN_LINK}", "| <a class='link-3' href='/auth.php'>entrance for admin</a>" );
	}
	$tpl->assign( "{TITLE}", '' );
	$tpl->assign( "{COOK_NICK}", isset( $_COOKIE['nick'] ) ? $_COOKIE['nick'] : NULL );
	$tpl->assign( "{COOK_EMAIL}", isset( $_COOKIE['email'] ) ? $_COOKIE['email'] : NULL );


	$thumbs = get_random_elements( recursive_dir( "files/portfolio", ".jpg", [ 'thumb' ], [ ], false ), 7 ); // сканирование в субдеррикториях 'thumb'
	$tpl->assign( "{A_IMG_COMM}", "/portfolio.php" );
	foreach ( $thumbs as $thumb ) {

		$thumb = WinUtf( $thumb[1], 'w' );
		$tpl->assign( "{THUMB_IMG_COMM}", $thumb );
		$tpl->parse( "{FOTO_COMM}", ".foto_c" );
	}


	Db::where( "flag", 1 );
	$total = Db::getOne( TBL_POSTS, "count(*) as records" ); // всего разрешенных записей
	if ( $total['records'] ) {
		$pages = $total['records'] / $perpage; // рассчетное число страниц
	} else {
		$pages = 1;
	}

	if ( ( $pages + 1 ) / ( (int) $pages + 1 ) <> 1 ) {
		$pages = (int) $pages + 1;
	}
	$offset = 0;
	if ( isset( $_GET['offset'] ) ) {
		$offset = intval( $_GET['offset'] );
	}
	if ( $offset > $total['records'] || !is_numeric( $offset ) ) {
		$offset = 0; // текущая страница
	}
	$pagenow = is_null( $offset ) ? 1 : ( $offset / $perpage + 1 );
	$tpl->assign( "{PAGE_NOW}", $pagenow );
	$tpl->assign( "{TOTAL_PAGES}", $pages );
	$tpl->assign( "{TOTAL_SHOW_MESS}", $total['records'] );

	$next     = $offset + $perpage;
	$previous = $offset - $perpage;
	if ( $pages <> 1 ) {
		if ( $previous < 0 ) {
			$tpl->assign( "{PAGE_NEXT}", "<a href=" . $_SERVER['PHP_SELF'] . "?offset=" . $next . ">следующие " . $perpage . " записей</a> &raquo;" );
			$tpl->assign( "{PAGE_PREV}", '' );
		} elseif ( $next >= $total['records'] ) {
			$tpl->assign( "{PAGE_PREV}", "&laquo; <a href=" . $_SERVER['PHP_SELF'] . "?offset=" . $previous . ">предыдущие " . $perpage . " записей</a>" );
			$tpl->assign( "{PAGE_NEXT}", '' );
		} else {
			$tpl->assign( "{PAGE_PREV}", "&laquo; <a href=" . $_SERVER['PHP_SELF'] . "?offset=" . $previous . ">предыдущие " . $perpage . " записей</a> |" );
			$tpl->assign( "{PAGE_NEXT}", " <a href=" . $_SERVER['PHP_SELF'] . "?offset=" . $next . ">следующие " . $perpage . " записей</a> &raquo;" );
		}
	} else {
		$tpl->assign( "{PAGE_PREV}", '' );
		$tpl->assign( "{PAGE_NEXT}", '' );
	}
	$i         = 0;
	$page_list = '';
	while ( $i < $pages ) {
		$ri       = $i + 1;
		$showpage = $i * $perpage;
		if ( $ri == $pagenow ) {
			$page_list = '';
		} else {
			$page_list .= "<a href=comments.php?offset=" . $showpage . ">" . $ri . "</a> ";
		}
		$i ++;
	}
	$tpl->assign( "{PAGES}", $page_list );
	Db::where( "flag", 1 );
	Db::orderBy( "create_time", "DESC" );
	$resutls = Db::get( TBL_POSTS, [ $offset, $perpage ] );

	if ( count( $resutls ) > 0 ) {

		$gravatar          = new Gravatar();
		$gravatar->default = "http://www.annafoto.in.ua/images/blocks/no_avatar.jpg";
		$gravatar->size    = 40;
		$gravatar->extra   = "alt='Аватар' class = 'avatar'";

		foreach ( $resutls as $row ) {

			$gravatar->email = $row['email'];
			$tpl->assign( "{GRAVATAR}", $gravatar->toHTML() );
			$tpl->assign( "{ID}", $row['id'] );
			$tpl->assign( "{AUTHOR}", $row['poster'] );
			$tpl->assign( "{EMAIL}", ( !empty( $row['email'] ) ) ? "E-mail: <a href='mailto:" . $row['email'] . "'>" . $row['email'] . "</a>" : '' );
			$tpl->assign( "{TXT}", nl2br( strip_tags( $row['text'], "<b><i><u>" ) ) );
			$tpl->assign( "{TIME_CREATED}", VrTime( $row['create_time'] ) );

			Db::where( "parent", $row['id'] );
			Db::orderBy( "create_time", "DESC" );
			$rep = Db::get( TBL_REPLIES, NULL, [ 'poster', 'reply' ] );

			if ( count( $rep ) > 0 ) {
				foreach ( $rep as $reply ) {

					Db::where( "id", $reply['poster'] );
					$query = Db::get( TBL_USERS, NULL, [ 'login', 'email' ] );

					$gravatar->email   = $query[0]['email'];
					$gravatar->default = "http://www.annafoto.in.ua/images/avtor.jpg";
					$tpl->assign( "{GRAVATAR_AUTHOR}", $gravatar->toHTML() );
					$tpl->assign( "{REP_AUTHOR}", $query[0]['login'] );
					$tpl->assign( "{REPLY}", nl2br( strip_tags( $reply['reply'], "<b><i><u>" ) ) );
					$tpl->parse( "{REPLIES}", ".rep" );
				}
				$tpl->clear( "{REPLIES}" );
			} else {
				$tpl->assign( "{REPLIES}", '' );
			}
			$tpl->assign( "{IP}", $row['ip'] );
			if ( isset( $_SESSION['logged'] ) && $_SESSION['logged'] == true ) {
				$tpl->parse( "{ADMIN_OPTS}", ".adm_opts" );
				$tpl->clear( "{ADMIN_OPTS}" );
			} else {
				$tpl->assign( "{ADMIN_OPTS}", '' );
				$tpl->clear( "{ADMIN_OPTS}" );
			}
			$tpl->parse( "{MESSAGES}", ".mess" );
		}
	} else {
		$tpl->assign( "{MESSAGES}", "Сообщений пока нет." );
	}

	$tpl->parse( "HEAD", "head" );
	$tpl->parse( "FORM", "form" );

	$tpl->FastPrint( "HEAD" );
	$tpl->FastPrint( "FORM" );

	$end = $tpl->utime();
	// время генерации скрипта
	$tpl->assign( "{GEN_TIME}", sprintf( "%01.3f ", ( $end - $begin ) ) );
	$tpl->parse( "FOOT", "foot" );
	$tpl->FastPrint( "FOOT" );


} else {
	if ( isset( $_SESSION['l_act'] ) && ( time() - $_SESSION['l_act'] ) <= 300 &&
		( !isset( $_SESSION['logged'] ) || isset( $_SESSION['logged'] ) && !$_SESSION['logged'] )
	) {
		$tpl->assign( "{ERROR}", "Вы не можете отправить следующее сообщение меньше, чем через пять минут после предыдущего." );
		$tpl->parse( "ERROR", "error" );
		$tpl->FastPrint( "ERROR" );
	} else if ( $_POST['name'] != 'cool' ) {
		$tpl->assign( "{ERROR}", "Хакер? Ip адрес вашего компьютра зафиксирован и отправлен в службу безопасности провайдера." );
		$tpl->parse( "ERROR", "error" );
		$tpl->FastPrint( "ERROR" );
		?>
		<script type="text/javascript">
			function moveTo() {
				location.href = "<?=$_SERVER['HTTP_HOST']?>";
			}
			window.onload = setTimeout("moveTo()", 8000);
		</script>
	<?
	} else if ( empty( $_POST['nick'] ) ) {
		$tpl->assign( "{ERROR}", "Поле 'Ваше имя' является обязательным. Пожалуйста, вернитесь и заполните." );
		$tpl->parse( "ERROR", "error" );
		$tpl->FastPrint( "ERROR" );
	} else if ( empty( $_POST['mess'] ) ) {
		$tpl->assign( "{ERROR}", "Поле 'Ваше сообщение' является обязательным. Пожалуйста, вернитесь и заполните." );
		$tpl->parse( "ERROR", "error" );
		$tpl->FastPrint( "ERROR" );
	} else {

		/*setcookie("nick", $_POST['nick'], time()+300);
		setcookie("email", $_POST['email'], time()+300);*/ // перенес в шапку
		$_SESSION['l_act'] = time();

		$values = [
			"poster"      => $_POST['nick'],
			"email"       => $_POST['email'],
			"text"        => $_POST['mess'],
			"ip"          => ip(),
			"create_time" => time()
		];
		Db::insert( TBL_POSTS, $values );
		Db::orderBy( 'id' );
		$q = Db::get( TBL_USERS, NULL, [ 'login', 'email' ] );

		foreach ( $q as $row ) {

			mail(
				$row['email'],
				'Новое сообщение в гостевой книге',
				'Здравствуйте, ' . $row['login'] .
				"!\n\nВ гостевую книгу было отправлено следующее сообщение:\n\nАвтор: " . $_POST['nick'] . "\nE-mail: " .
				$_POST['email'] . "\nСообщение:\n" . $_POST['mess'] . "\n\n------\nЧтобы открыть это сообщение, пройдите по следующей ссылке:\nhttp://" .
				$_SERVER['SERVER_NAME'] . dirname( $_SERVER['PHP_SELF'] ) . "/admin.php?mode=show&id=" . Db::getLastQuery() . "\nУдаление:\nhttp://" .
				$_SERVER['SERVER_NAME'] . dirname( $_SERVER['PHP_SELF'] ) . "/admin.php?mode=del&id=" . Db::getLastQuery() . "\nРедактирование: http://" .
				$_SERVER['SERVER_NAME'] . dirname( $_SERVER['PHP_SELF'] ) . "/admin.php?mode=edit&id=" . Db::getLastQuery() . "\nНаписать ответ: http://" .
				$_SERVER['SERVER_NAME'] . dirname( $_SERVER['PHP_SELF'] ) . "/admin.php?mode=show&id=" . Db::getLastQuery(),
				"From: " . $_SERVER['SERVER_NAME'] );
		}

		$tpl->assign( "{TITLE}", "- спасибо за сообщение." );
		$tpl->parse( "HEAD", "head" );
		$tpl->assign( "{POSTER}", htmlspecialchars( $_POST['nick'], null, "windows-1251" ) );
		$tpl->assign( "{EMAIL}", htmlspecialchars( $_POST['email'], null, "windows-1251" ) );
		$tpl->assign( "{TEXT}", nl2br( strip_tags( $_POST['mess'], "<b><i><u>" ) ) );
		$tpl->parse( "SENT", "sent" );
		$tpl->FastPrint( "HEAD" );
		$tpl->FastPrint( "SENT" );
		$end = $tpl->utime();
		// время генерации скрипта
		$tpl->assign( "{GEN_TIME}", sprintf( "%01.3f ", ( $end - $begin ) ) );
		$tpl->parse( "FOOT", "foot" );
		$tpl->FastPrint( "FOOT" );

	}

}

?>
	<!--==============================footer================================-->
<?

$_SESSION['referer'] = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : $_SERVER['PHP_SELF'];
include_once( __DIR__ . '/inc/footer.php' );
?>