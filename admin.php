<?
require(__DIR__ .'/system/config/config.php'); // старт сессии, автолоадер, подключение базы, обработчик ошибок, файл функций

IF ( isset( $_GET['adm_out'] ) || !isset( $_SESSION['logged'] ) || $_SESSION['logged'] != TRUE ) {
	unset( $_SESSION['id'] );
	unset( $_SESSION['logged'] );
	unset( $_SESSION['nick'] );
	if ( isset( $_SESSION['admnews'] ) ) unset( $_SESSION['admnews'] );
	unset( $_COOKIE['nick'] );
	header( 'Location: ' . "/auth.php", true, 303 );
}

$db = Db::getInstance( Db::getParam() );

$tpl = new Comments\Template( "classes/Comments/_templates/" );
$tpl->define( [
	"head"        => "head.tpl",
	"index"       => "adm_index.tpl",
	"edit_index"  => "adm_edit_index.tpl",
	"users_index" => "adm_us_index.tpl",
	"edit_mes"    => "adm_edit_mes.tpl",
	"edit_rep"    => "adm_edit_rep.tpl",
	"edit_us"     => "adm_edit_us.tpl",
	"list_mes"    => "adm_list_mes.tpl",
	"list_rep"    => "adm_list_rep.tpl",
	"list_us"     => "adm_list_us.tpl",
	"error"       => "error.tpl",
	"foot"        => "foot.tpl"
] );
$return_page = null;
if ( isset($_REQUEST['mode']) && isset($_REQUEST['id']) && $_REQUEST['mode'] == ('reply' || 'edit') ) {
	$return_page = "<a href='./admin.php?mode=edit'>к таблице сообщений</a>";
} else if ( isset( $_REQUEST['act'] ) && $_REQUEST['act'] == 'edit' ) {
	$return_page = "<a href='./admin.php?mode=users'>к таблице модераторов</a>";
} else if ( isset( $_REQUEST['mode']) && $_REQUEST['mode'] == ('users' || 'edit')) {
	$return_page = "<a href='./admin.php'>к выбору действий</a>";
} else {
	$return_page = "<a href='./comments.php'>в гостевую</a>";;
}

$start = $tpl->utime();
$nick  = isset( $_SESSION['nick'] ) ? "<a href='#'>вход выполнил(а): '" . $_SESSION['nick'] . "'</a> |" : FALSE;
$tpl->assign( "{SCRIPT}", $_SERVER['PHP_SELF'] );
$tpl->assign( "{ADM_MENU}",
	"<a href='./comments.php' style='padding-left: 20px;'>гостевая</a> | {$return_page}
<span class='fright'>" . $nick . " <a href='./admin.php?adm_out=1' style='padding-right: 20px;'>выход</a></span>" );
$mode   = isset( $_GET['mode'] ) ? $_GET['mode'] : FALSE;
$ids    = isset( $_POST['ids'] ) ? $_POST['ids'] : FALSE;
$id     = isset( $_GET['id'] ) ? $_GET['id'] : FALSE;
$ses_id = isset( $_SESSION['id'] ) ? $_SESSION['id'] : FALSE;
$return = isset( $_SESSION['referer'] ) ? $_SESSION['referer'] : $_SERVER['PHP_SELF'] . "?mode=edit";
SWITCH ( $mode ) {

	case 'mass':
		$act = isset( $_POST['act'] ) ? $_POST['act'] : FALSE;
		SWITCH ( $act ) {
			default:
				header( "Location: " . $_SERVER['PHP_SELF'] . "?mode=edit" );
				exit;
				break;

			case 'del':
				if ( !is_array( $ids ) ) {
					header( "Location: " . $_SERVER['PHP_SELF'] . "?mode=edit" );
					exit;
				} else {
					for ( $i = 0; $i < count( $ids ); $i ++ ) {
						$db->where( 'id', $ids[$i] );
						$db->delete( $GLOBALS['tbl_posts'] );
					}
					//	header("Location: ".$_SERVER['PHP_SELF']."?mode=edit"); exit;
					header( "Location: " . $return );
					exit;
				}
				break;

			case 'show':
				if ( !is_array( $ids ) ) {
					header( "Location: " . $_SERVER['PHP_SELF'] . "?mode=edit" );
					exit;
				} else {

					for ( $i = 0; $i < count( $ids ); $i ++ ) {
						$db->where( 'id', $ids[$i] );
						$vars = [
							'flag' => '1'
						];
						$db->update( $GLOBALS['tbl_posts'], $vars );
					}
					header( "Location: " . $_SERVER['PHP_SELF'] . "?mode=edit" );
					exit;
				}
				break;

			case 'hide':
				if ( !is_array( $ids ) ) {
					header( "Location: " . $_SERVER['PHP_SELF'] . "?mode=edit" );
					exit;
				} else {
					for ( $i = 0; $i < count( $ids ); $i ++ ) {
						$db->where( 'id', $ids[$i] );
						$vars = [
							'flag' => '0'
						];
						$db->update( $GLOBALS['tbl_posts'], $vars );
					}
					header( "Location: " . $_SERVER['PHP_SELF'] . "?mode=edit" );
					exit;
				}
				break;
		}
		break;

	case 'reply':
		IF ( !$id ) {
			header( "Location: " . $_SERVER['PHP_SELF'] . "?mode=edit" );
			exit;
		} ELSE {

			$db->where( "id", $id );
			$q    = $db->get( $GLOBALS['tbl_posts'], 1, [ 'poster', 'email', 'text' ] );
			$test = count( $q );
			if ( count( $q ) != 0 ) {

				$poster = $email = $text = NULL;
				extract( $q[0], EXTR_OVERWRITE );
				$db->where( "parent", $id );
				$db->where( "poster", $ses_id );
				$q = $db->get( $GLOBALS['tbl_replies'], 1, [ 'id', 'reply', 'poster' ] );

				if ( count( $q ) != 0 ) {
					$_id = $_reply = $_poster = NULL;
					extract( $q[0], EXTR_PREFIX_ALL, "" );

					$db->where( "id", $_poster );
					$get = $db->getOne( $GLOBALS['tbl_users'], 'login' );

					if ( !isset( $_POST['submit'] ) ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{TITLE}", "- admin section - Replying" );
						$tpl->assign( "{ID}", $id );
						$tpl->assign( "{REP_ID}", $_id );
						$tpl->assign( "{POSTER}", $poster );
						$tpl->assign( "{EMAIL}", $email );
						$tpl->assign( "{TEXT}", $text );
						$tpl->assign( "{REP_POSTER}", $get['login'] );
						$tpl->assign( "{REPLY}", $_reply );
						$tpl->parse( "HEAD", "head" );
						$tpl->parse( "INDEX", "edit_rep" );
						$tpl->FastPrint( "HEAD" );
						$tpl->FastPrint( "INDEX" );
					} else {

						$db->where( 'id', $_POST['rep_id'] );
						$vars = [
							'reply'       => $_POST['reply'],
							'create_time' => time()
						];
						$db->update( $GLOBALS['tbl_replies'], $vars );
						//	header("Location: ".$_SERVER['PHP_SELF']."?mode=edit"); exit;
						header( "Location: " . $return );
						exit;
					}
				} else {

					$db->where( "id", $ses_id );
					$get = $db->getOne( $GLOBALS['tbl_users'], 'login' );

					if ( !isset( $_POST['submit'] ) ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{TITLE}", "- admin section - Replying" );
						$tpl->assign( "{ID}", $id );
						$tpl->assign( "{REP_ID}" );
						$tpl->assign( "{POSTER}", $poster );
						$tpl->assign( "{EMAIL}", $email );
						$tpl->assign( "{TEXT}", $text );
						$tpl->assign( "{REP_POSTER}", $get['login'] );
						$tpl->assign( "{REPLY}" );
						$tpl->parse( "HEAD", "head" );
						$tpl->parse( "INDEX", "edit_rep" );
						$tpl->FastPrint( "HEAD" );
						$tpl->FastPrint( "INDEX" );
					} else if ( empty( $_POST['reply'] ) ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "Вы ничего не ввели в поле 'Ответ'." );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );
					} else {
						$values = [
							"parent"      => $id,
							"poster"      => $ses_id,
							"reply"       => $_POST['reply'],
							"create_time" => time()
						];
						$db->insert( $GLOBALS['tbl_replies'], $values );
						//	header("Location: ".$_SERVER['PHP_SELF']."?mode=edit");
						header( "Location: " . $return );
						exit;
					}
				}
			}
		}
		break;

	case 'show':
		IF ( !$id ) {
			header( "Location: " . $_SERVER['PHP_SELF'] . "?mode=edit" );
			exit;
		} ELSE {
			$db->where( 'id', $id );
			$vars = [ 'flag' => '1' ];
			$db->update( $GLOBALS['tbl_posts'], $vars );
			//	header("Location: ".$_SERVER['PHP_SELF']."?mode=edit"); exit;
			header( "Location: " . $_SERVER['HTTP_REFERER'] );
			exit;
		}
		break;

	case 'hide':
		IF ( !$id ) {
			header( "Location: " . $_SERVER['PHP_SELF'] . "?mode=edit" );
			exit;
		} ELSE {
			$db->where( 'id', $id );
			$vars = [ 'flag' => '0' ];
			$db->update( $GLOBALS['tbl_posts'], $vars );
			//	header("Location: ".$_SERVER['PHP_SELF']."?mode=edit"); exit;
			header( "Location: " . $_SERVER['HTTP_REFERER'] );
			exit;
		}
		break;

	case 'del':
		IF ( !$id ) {
			header( "Location: " . $_SERVER['PHP_SELF'] . "?mode=edit" );
			exit;
		} ELSE {
			$db->where( 'id', $id );
			$db->delete( $GLOBALS['tbl_posts'] );
			$db->where( 'parent', $id );
			$db->delete( $GLOBALS['tbl_replies'] );
			//	header("Location: ".$_SERVER['PHP_SELF']."?mode=edit"); exit;
			header( "Location: " . $_SERVER['HTTP_REFERER'] );
			exit;
		}
		break;

	case 'edit':
		IF ( !$id ) {
			include( __DIR__ . "/inc/head.php" );
			$db->orderBy( "create_time" );
			$q = $db->get( $GLOBALS['tbl_posts'] );
			$tpl->assign( "{TITLE}", "- admin section - Editing" );
			$tpl->parse( "HEAD", "head" );
			$tpl->FastPrint( "HEAD" );
			if ( $q ) {
				foreach ( $q as $row ) {
					$db->where( "parent", $row['id'] );
					$db->orderBy( "create_time" );
					$c = $db->get( $GLOBALS['tbl_replies'] );
					if ( count( $c ) > 0 ) {
						foreach ( $c as $rep ) {
							$db->where( "id", $rep['poster'] );
							$poster = $db->getOne( $GLOBALS['tbl_users'], "login" );
							$tpl->assign( "{REP_AUTHOR}", $poster['login'] );
							$tpl->assign( "{REPLY}", nl2br( htmlspecialchars( $rep['reply'], ENT_QUOTES, "windows-1251" ) ) );
							$tpl->parse( "{REPLIES}", ".list_rep" );
							$tpl->clear( "{REPLIES}" );
						}
					} else {
						$tpl->assign( "{REPLIES}", "Ответов на это сообщение пока нет." );
					}
					if ( $row['flag'] == '1' ) {
						$tpl->assign( "{TD_STYLE}", "show-post" );
						$tpl->assign( "{SHOW_HIDE}", "<a href='" . $_SERVER['PHP_SELF'] . "?mode=hide&id=" . $row['id'] . "'>спрятать</a>" );
					} else {
						$tpl->assign( "{TD_STYLE}", "hide-post" );
						$tpl->assign( "{SHOW_HIDE}", "<a href='" . $_SERVER['PHP_SELF'] . "?mode=show&id=" . $row['id'] . "'>показать</a>" );
					}
					$tpl->assign( "{ID}", $row['id'] );
					$tpl->assign( "{POSTER}", $row['poster'] );
					$tpl->assign( "{EMAIL}", $row['email'] );
					$tpl->assign( "{TEXT}", nl2br( htmlspecialchars( $row['text'], ENT_QUOTES, "windows-1251" ) ) );
					$tpl->assign( "{IP}", $row['ip'] );
					$tpl->assign( "{CREATE_TIME}", VrTime( $row['create_time'] ) );
					$tpl->parse( "{LISTING}", ".list_mes" );
				}
			} else {
				$tpl->assign( "{LISTING}", "<h2 class='center' style='font-size: 18px;'>Сообщений пока нет</h2>" );
			}
			$tpl->parse( "INDEX", "edit_index" );
			$tpl->FastPrint( "INDEX" );
		} ELSE {
			if ( !isset( $_POST['submit'] ) ) {
				include( __DIR__ . "/inc/head.php" );
				$db->where( "id", $id );
				$q = $db->get( $GLOBALS['tbl_posts'], 1, [ 'poster', 'text', 'email', 'flag' ] );
				if ( count( $q ) == '0' ) {
					$tpl->assign( "{ERROR}", "Сообщения с таким ID не существует." );
					$tpl->parse( "ERROR", "error" );
					$tpl->FastPrint( "ERROR" );
					exit;
				} else {
					$poster = $text = $email = $flag = NULL;
					extract( $q[0], EXTR_OVERWRITE );
					( $flag == '1' ? $status = "видимо" : $status = "скрыто" );
					$tpl->assign( "{TITLE}", "- admin section - Editing msg #" . $id );
					$tpl->parse( "HEAD", "head" );
					$tpl->assign( "{ID}", $id );
					$tpl->assign( "{POSTER}", $poster );
					$tpl->assign( "{EMAIL}", $email );
					$tpl->assign( "{TEXT}", $text );
					$tpl->assign( "{STATUS}", $status );
					$tpl->parse( "INDEX", "edit_mes" );
					$tpl->FastPrint( "HEAD" );
					$tpl->FastPrint( "INDEX" );
				}
			} else {
				if ( empty( $_POST['nick'] ) ) {
					include( __DIR__ . "/inc/head.php" );
					$tpl->assign( "{ERROR}", "Поле 'Nick' является обязательным. Вернитесь и заполните." );
					$tpl->parse( "ERROR", "error" );
					$tpl->FastPrint( "ERROR" );
				} else if ( empty( $_POST['mess'] ) ) {
					include( __DIR__ . "/inc/head.php" );
					$tpl->assign( "{ERROR}", "Поле 'Message' является обязательным. Вернитесь и заполните." );
					$tpl->parse( "ERROR", "error" );
					$tpl->FastPrint( "ERROR" );

				} else {
					$db->where( 'id', $id );
					$vars = [
						'poster' => $_POST['nick'],
						'text'   => $_POST['mess'],
						'email'  => $_POST['email'],
						'flag'   => $_POST['flag']
					];
					$db->update( $GLOBALS['tbl_posts'], $vars );
					header( "Location: " . $return );
					exit;
				}
			}
		}
		break;

	case 'users':
		$get_act = isset( $_GET['act'] ) ? $_GET['act'] : FALSE;
		SWITCH ( $get_act ) {
			case 'edit':
				IF ( !$id ) {
					header( "Location: " . $_SERVER['PHP_SELF'] . "?mode=" . $mode );
					exit;
				} ELSE {
					if ( $ses_id != $id && $ses_id != '1' ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "Вы не имеете права изменять данные этого пользователя!" );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );
					} else if ( !isset( $_POST['submit'] ) ) {
						include( __DIR__ . "/inc/head.php" );
						$db->where( "id", $id );
						$c      = $db->get( $GLOBALS['tbl_users'], 1, [ 'login', 'email' ] );
						$_login = $_email = NULL;
						extract( $c[0], EXTR_PREFIX_ALL, "" );
						$tpl->assign( "{TITLE}", "- admin section - Users - Создание нового модератора" );
						$tpl->parse( "HEAD", "head" );
						$tpl->FastPrint( "HEAD" );
						$tpl->assign( "{LOGIN}", $_login );
						$tpl->assign( "{EMAIL}", $_email );
						$tpl->assign( "{TEXT}", "Этот раздел для изменения данных уже существующих модераторов. Если вы желаете изменить пароль,<br> то введите его два раза - если нет, оставьте поля пустыми." );
						$tpl->assign( "{FORM}", $_SERVER['PHP_SELF'] . "?mode=" . $mode . "&act=" . $get_act . "&id=" . $id );
						$tpl->assign( "{PASS_TEXT}", "Если вы не собираетесь менять пароль, оставьте оба поля пустыми" );
						$tpl->parse( "INDEX", "edit_us" );
						$tpl->FastPrint( "INDEX" );
					} else if ( empty( $_POST['login'] ) ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "Поле 'Login' является обязательным. Вернитесь и заполните." );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );

					} else if ( empty( $_POST['email'] ) ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "Поле 'E-mail' является обязательным. Вернитесь и заполните." );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );

					} else if ( $_POST['p_1'] != $_POST['p_2'] ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "Введенные пароли не совпадают!" );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );

					} else if ( empty( $_POST['p_1'] ) ) {
						$db->where( 'id', $id );
						$vars = [
							'login' => $_POST['login'],
							'email' => $_POST['email']
						];
						$db->update( $GLOBALS['tbl_users'], $vars );
						//	header("Location: ".$_SERVER['PHP_SELF']."?mode=".$mode); exit;
						header( "Location: " . $return );
						exit;
					} else {
						$db->where( 'id', $id );
						$vars = [
							'login' => $_POST['login'],
							'email' => $_POST['email'],
							'pass'  => md5( $_POST['p_1'] )
						];
						$db->update( $GLOBALS['tbl_users'], $vars );
						//	header("Location: ".$_SERVER['PHP_SELF']."?mode=".$mode); exit;
						header( "Location: " . $return );
						exit;
					}
				}
				break;

			case 'del':
				IF ( !$id ) {
					header( "Location: " . $_SERVER['PHP_SELF'] . "?mode=" . $mode );
					exit;
				} ELSE {

					$db->where( 'id', '1' );
					$get = $db->getOne( $GLOBALS['tbl_users'], 'login' );

					if ( $ses_id != '1' ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "Правом удаления пользователей обладает только <b>" . $get['login'] . "</b>." );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );
						exit;
					}

					if ( $id == '1' ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "Первая учетная запись не может быть удалена." );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );
						exit;
					}

					$db->where( 'id', $id );
					$_login = $db->delete( $GLOBALS['tbl_users'] );
					header( "Location: " . $_SERVER['PHP_SELF'] . "?mode=" . $mode );
					exit;
				}
				break;

			case 'new':
				if ( $ses_id != '1' ) {
					include( __DIR__ . "/inc/head.php" );

					$db->where( 'id', '1' );
					$get = $db->getOne( $GLOBALS['tbl_users'], 'login' );
					$tpl->assign( "{ERROR}", "Правом создания пользователей обладает только <b>" . $get['login'] . "</b>." );
					$tpl->parse( "ERROR", "error" );
					$tpl->FastPrint( "ERROR" );
					exit;
				}

				if ( !isset( $_POST['submit'] ) ) {
					include( __DIR__ . "/inc/head.php" );
					$tpl->assign( "{TITLE}", "- admin section - Users - Создание нового модератора" );
					$tpl->parse( "HEAD", "head" );
					$tpl->FastPrint( "HEAD" );
					$tpl->assign( "{LOGIN}" );
					$tpl->assign( "{EMAIL}" );
					$tpl->assign( "{TEXT}", "Здесь вы можете создать нового модератора. Все поля надо заполнить обязательно." );
					$tpl->assign( "{FORM}", $_SERVER['PHP_SELF'] . "?mode=" . $mode . "&act=" . $get_act );
					$tpl->assign( "{PASS_TEXT}", "Заполните, пожалуйста, оба поля и запомните пароль." );
					$tpl->parse( "INDEX", "edit_us" );
					$tpl->FastPrint( "INDEX" );
				} else {
					if ( empty( $_POST['login'] ) ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "Поле 'Login' является обязательным. Вернитесь и заполните." );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );
						exit;
					}

					if ( empty( $_POST['email'] ) ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "Поле 'E-mail' является обязательным. Вернитесь и заполните." );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );
						exit;
					}

					if ( empty( $_POST['p_1'] ) ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "Поля для пароля являются обязательными. Вернитесь и заполните." );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );
						exit;
					}

					if ( $_POST['p_1'] != $_POST['p_2'] ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "Введенные пароли не совпадают!" );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );
						exit;
					}
					$db->where( 'login', $_POST['login'] );
					$q = $db->getOne( $GLOBALS['tbl_users'], 'login' );
					if ( count( $q ) > 0 ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "Такая учетная запись уже существует в базе данных." );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );
						exit;
					}
					$values = [
						"login" => $_POST['login'],
						"pass"  => md5( $_POST['p_1'] ),
						"email" => $_POST['email']
					];
					$db->insert( $GLOBALS['tbl_users'], $values );
					header( "Location: " . $_SERVER['PHP_SELF'] . "?mode=" . $mode );
					exit;
				}
				break;

			default:
				include( __DIR__ . "/inc/head.php" );
				$tpl->assign( "{TITLE}", "- admin section - Users" );
				$tpl->parse( "HEAD", "head" );
				$tpl->FastPrint( "HEAD" );
			//	$db->check_connect();
				$db->orderBy( 'id', 'ASC' );
				$q = $db->get( $GLOBALS['tbl_users'], NULL, [ 'id', 'login', 'email' ] );

				foreach ( $q as $row ) {
					$tpl->assign( "{ID}", $row['id'] );
					$tpl->assign( "{LOGIN}", $row['login'] );
					$tpl->assign( "{EMAIL}", $row['email'] );
					if ( $ses_id == '1' ) {
						$tpl->assign( "{NEW_LINK}", "| <a href='" . $_SERVER['PHP_SELF'] . "?mode=" . $mode . "&act=new'>добавить нового модератора </a>" );
						if ( $row['id'] != "1" ) {
							$tpl->assign( "{DEL_LINK}", "<a href='" . $_SERVER['PHP_SELF'] . "?mode=" . $mode . "&act=del&id=" . $row['id'] . "'>удалить</a>" );
						} else {
							$tpl->assign( "{DEL_LINK}", "<a href='#'>нет действия</a>" );
						}
					} else {
						$tpl->assign( "{NEW_LINK}", "" );
						$tpl->assign( "{DEL_LINK}", "вы не имеете права удалять<br> пользователей" );
					}
					$tpl->assign( "{EDIT_LINK}", "<a href='" . $_SERVER['PHP_SELF'] . "?mode=" . $mode . "&act=edit&id=" . $row['id'] . "'>изменить данные</a>" );
					$tpl->parse( "{LIST_USERS}", ".list_us" );
				}

				$tpl->parse( "INDEX", "users_index" );
				$tpl->FastPrint( "INDEX" );
				break;
		}
		break;
	default:
		include( __DIR__ . "/inc/head.php" );
		$tpl->assign( "{TITLE}", "- admin section" );
		$tpl->parse( "HEAD", "head" );
		$tpl->parse( "INDEX", "index" );
		$tpl->FastPrint( "HEAD" );
		$tpl->FastPrint( "INDEX" );
		break;
}
$_SESSION['referer'] = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : $_SERVER['PHP_SELF'];
$end                 = $tpl->utime();
$tpl->assign( "{GEN_TIME}", sprintf( "%01.3f ", ( $end - $start ) ) );
$tpl->parse( "FOOT", "foot" );
$tpl->FastPrint( "FOOT" );
include( __DIR__ . "/inc/footer.php" );