<?

use proxy\Db as Db;

require(__DIR__ . '/src/config/config.php'); // ����� ������, ����������, ����������� ����, ���������� ������, ���� �������

IF ( isset( $_GET['adm_out'] ) || !isset( $_SESSION['logged'] ) || $_SESSION['logged'] != true) {
	unset( $_SESSION['id'] );
	unset( $_SESSION['logged'] );
	unset( $_SESSION['nick'] );
	if ( isset( $_SESSION['admnews'] ) ) unset( $_SESSION['admnews'] );
	unset( $_COOKIE['nick'] );
	header( 'Location: ' . "/auth.php", true, 303 );
}

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
	$return_page = "<a href='./admin.php?mode=edit'>� ������� ���������</a>";
} else if ( isset( $_REQUEST['act'] ) && $_REQUEST['act'] == 'edit' ) {
	$return_page = "<a href='./admin.php?mode=users'>� ������� �����������</a>";
} else if ( isset( $_REQUEST['mode']) && $_REQUEST['mode'] == ('users' || 'edit')) {
	$return_page = "<a href='./admin.php'>� ������ ��������</a>";
} else {
	$return_page = "<a href='./comments.php'>� ��������</a>";;
}

$start = $tpl->utime();
$nick  = isset( $_SESSION['nick'] ) ? "<a href='#'>���� ��������(�): '" . $_SESSION['nick'] . "'</a> |" : FALSE;
$tpl->assign( "{SCRIPT}", $_SERVER['PHP_SELF'] );
$tpl->assign( "{ADM_MENU}",
	"<a href='./comments.php' style='padding-left: 20px;'>��������</a> | {$return_page}
<span class='fright'>" . $nick . " <a href='./admin.php?adm_out=1' style='padding-right: 20px;'>�����</a></span>" );
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
						Db::where( 'id', $ids[$i] );
						Db::delete( TBL_POSTS );
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
						Db::where( 'id', $ids[$i] );
						$vars = [
							'flag' => '1'
						];
						Db::update( TBL_POSTS, $vars );
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
						Db::where( 'id', $ids[$i] );
						$vars = [
							'flag' => '0'
						];
						Db::update( TBL_POSTS, $vars );
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

			Db::where( "id", $id );
			$q    = Db::get( TBL_POSTS, 1, [ 'poster', 'email', 'text' ] );
			$test = count( $q );
			if ( count( $q ) != 0 ) {

				$poster = $email = $text = NULL;
				extract( $q[0], EXTR_OVERWRITE );
				Db::where( "parent", $id );
				Db::where( "poster", $ses_id );
				$q = Db::get( TBL_REPLIES, 1, [ 'id', 'reply', 'poster' ] );

				if ( count( $q ) != 0 ) {
					$_id = $_reply = $_poster = NULL;
					extract( $q[0], EXTR_PREFIX_ALL, "" );

					Db::where( "id", $_poster );
					$get = Db::getOne( TBL_USERS, 'login' );

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

						Db::where( 'id', $_POST['rep_id'] );
						$vars = [
							'reply'       => $_POST['reply'],
							'create_time' => time()
						];
						Db::update( TBL_REPLIES, $vars );
						//	header("Location: ".$_SERVER['PHP_SELF']."?mode=edit"); exit;
						header( "Location: " . $return );
						exit;
					}
				} else {

					Db::where( "id", $ses_id );
					$get = Db::getOne( TBL_USERS, 'login' );

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
						$tpl->assign( "{ERROR}", "�� ������ �� ����� � ���� '�����'." );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );
					} else {
						$values = [
							"parent"      => $id,
							"poster"      => $ses_id,
							"reply"       => $_POST['reply'],
							"create_time" => time()
						];
						Db::insert( TBL_REPLIES, $values );
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
			Db::where( 'id', $id );
			$vars = [ 'flag' => '1' ];
			Db::update( TBL_POSTS, $vars );
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
			Db::where( 'id', $id );
			$vars = [ 'flag' => '0' ];
			Db::update( TBL_POSTS, $vars );
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
			Db::where( 'id', $id );
			Db::delete( TBL_POSTS );
			Db::where( 'parent', $id );
			Db::delete( TBL_REPLIES );
			//	header("Location: ".$_SERVER['PHP_SELF']."?mode=edit"); exit;
			header( "Location: " . $_SERVER['HTTP_REFERER'] );
			exit;
		}
		break;

	case 'edit':
		IF ( !$id ) {
			include( __DIR__ . "/inc/head.php" );
			Db::orderBy( "create_time" );
			$q = Db::get( TBL_POSTS );
			$tpl->assign( "{TITLE}", "- admin section - Editing" );
			$tpl->parse( "HEAD", "head" );
			$tpl->FastPrint( "HEAD" );
			if ( $q ) {
				foreach ( $q as $row ) {
					Db::where( "parent", $row['id'] );
					Db::orderBy( "create_time" );
					$c = Db::get( TBL_REPLIES );
					if ( count( $c ) > 0 ) {
						foreach ( $c as $rep ) {
							Db::where( "id", $rep['poster'] );
							$poster = Db::getOne( TBL_USERS, "login" );
							$tpl->assign( "{REP_AUTHOR}", $poster['login'] );
							$tpl->assign( "{REPLY}", nl2br( htmlspecialchars( $rep['reply'], ENT_QUOTES, "windows-1251" ) ) );
							$tpl->parse( "{REPLIES}", ".list_rep" );
							$tpl->clear( "{REPLIES}" );
						}
					} else {
						$tpl->assign( "{REPLIES}", "������� �� ��� ��������� ���� ���." );
					}
					if ( $row['flag'] == '1' ) {
						$tpl->assign( "{TD_STYLE}", "show-post" );
						$tpl->assign( "{SHOW_HIDE}", "<a href='" . $_SERVER['PHP_SELF'] . "?mode=hide&id=" . $row['id'] . "'>��������</a>" );
					} else {
						$tpl->assign( "{TD_STYLE}", "hide-post" );
						$tpl->assign( "{SHOW_HIDE}", "<a href='" . $_SERVER['PHP_SELF'] . "?mode=show&id=" . $row['id'] . "'>��������</a>" );
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
				$tpl->assign( "{LISTING}", "<h2 class='center' style='font-size: 18px;'>��������� ���� ���</h2>" );
			}
			$tpl->parse( "INDEX", "edit_index" );
			$tpl->FastPrint( "INDEX" );
		} ELSE {
			if ( !isset( $_POST['submit'] ) ) {
				include( __DIR__ . "/inc/head.php" );
				Db::where( "id", $id );
				$q = Db::get( TBL_POSTS, 1, [ 'poster', 'text', 'email', 'flag' ] );
				if ( count( $q ) == '0' ) {
					$tpl->assign( "{ERROR}", "��������� � ����� ID �� ����������." );
					$tpl->parse( "ERROR", "error" );
					$tpl->FastPrint( "ERROR" );
					exit;
				} else {
					$poster = $text = $email = $flag = NULL;
					extract( $q[0], EXTR_OVERWRITE );
					( $flag == '1' ? $status = "������" : $status = "������" );
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
					$tpl->assign( "{ERROR}", "���� 'Nick' �������� ������������. ��������� � ���������." );
					$tpl->parse( "ERROR", "error" );
					$tpl->FastPrint( "ERROR" );
				} else if ( empty( $_POST['mess'] ) ) {
					include( __DIR__ . "/inc/head.php" );
					$tpl->assign( "{ERROR}", "���� 'Message' �������� ������������. ��������� � ���������." );
					$tpl->parse( "ERROR", "error" );
					$tpl->FastPrint( "ERROR" );

				} else {
					Db::where( 'id', $id );
					$vars = [
						'poster' => $_POST['nick'],
						'text'   => $_POST['mess'],
						'email'  => $_POST['email'],
						'flag'   => $_POST['flag']
					];
					Db::update( TBL_POSTS, $vars );
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
						$tpl->assign( "{ERROR}", "�� �� ������ ����� �������� ������ ����� ������������!" );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );
					} else if ( !isset( $_POST['submit'] ) ) {
						include( __DIR__ . "/inc/head.php" );
						Db::where( "id", $id );
						$c      = Db::get( TBL_USERS, 1, [ 'login', 'email' ] );
						$_login = $_email = NULL;
						extract( $c[0], EXTR_PREFIX_ALL, "" );
						$tpl->assign( "{TITLE}", "- admin section - Users - �������� ������ ����������" );
						$tpl->parse( "HEAD", "head" );
						$tpl->FastPrint( "HEAD" );
						$tpl->assign( "{LOGIN}", $_login );
						$tpl->assign( "{EMAIL}", $_email );
						$tpl->assign( "{TEXT}", "���� ������ ��� ��������� ������ ��� ������������ �����������. ���� �� ������� �������� ������,<br> �� ������� ��� ��� ���� - ���� ���, �������� ���� �������." );
						$tpl->assign( "{FORM}", $_SERVER['PHP_SELF'] . "?mode=" . $mode . "&act=" . $get_act . "&id=" . $id );
						$tpl->assign( "{PASS_TEXT}", "���� �� �� ����������� ������ ������, �������� ��� ���� �������" );
						$tpl->parse( "INDEX", "edit_us" );
						$tpl->FastPrint( "INDEX" );
					} else if ( empty( $_POST['login'] ) ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "���� 'Login' �������� ������������. ��������� � ���������." );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );

					} else if ( empty( $_POST['email'] ) ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "���� 'E-mail' �������� ������������. ��������� � ���������." );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );

					} else if ( $_POST['p_1'] != $_POST['p_2'] ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "��������� ������ �� ���������!" );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );

					} else if ( empty( $_POST['p_1'] ) ) {
						Db::where( 'id', $id );
						$vars = [
							'login' => $_POST['login'],
							'email' => $_POST['email']
						];
						Db::update( TBL_USERS, $vars );
						//	header("Location: ".$_SERVER['PHP_SELF']."?mode=".$mode); exit;
						header( "Location: " . $return );
						exit;
					} else {
						Db::where( 'id', $id );
						$vars = [
							'login' => $_POST['login'],
							'email' => $_POST['email'],
							'pass'  => md5( $_POST['p_1'] )
						];
						Db::update( TBL_USERS, $vars );
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

					Db::where( 'id', '1' );
					$get = Db::getOne( TBL_USERS, 'login' );

					if ( $ses_id != '1' ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "������ �������� ������������� �������� ������ <b>" . $get['login'] . "</b>." );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );
						exit;
					}

					if ( $id == '1' ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "������ ������� ������ �� ����� ���� �������." );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );
						exit;
					}

					Db::where( 'id', $id );
					$_login = Db::delete( TBL_USERS );
					header( "Location: " . $_SERVER['PHP_SELF'] . "?mode=" . $mode );
					exit;
				}
				break;

			case 'new':
				if ( $ses_id != '1' ) {
					include( __DIR__ . "/inc/head.php" );

					Db::where( 'id', '1' );
					$get = Db::getOne( TBL_USERS, 'login' );
					$tpl->assign( "{ERROR}", "������ �������� ������������� �������� ������ <b>" . $get['login'] . "</b>." );
					$tpl->parse( "ERROR", "error" );
					$tpl->FastPrint( "ERROR" );
					exit;
				}

				if ( !isset( $_POST['submit'] ) ) {
					include( __DIR__ . "/inc/head.php" );
					$tpl->assign( "{TITLE}", "- admin section - Users - �������� ������ ����������" );
					$tpl->parse( "HEAD", "head" );
					$tpl->FastPrint( "HEAD" );
					$tpl->assign( "{LOGIN}" );
					$tpl->assign( "{EMAIL}" );
					$tpl->assign( "{TEXT}", "����� �� ������ ������� ������ ����������. ��� ���� ���� ��������� �����������." );
					$tpl->assign( "{FORM}", $_SERVER['PHP_SELF'] . "?mode=" . $mode . "&act=" . $get_act );
					$tpl->assign( "{PASS_TEXT}", "���������, ����������, ��� ���� � ��������� ������." );
					$tpl->parse( "INDEX", "edit_us" );
					$tpl->FastPrint( "INDEX" );
				} else {
					if ( empty( $_POST['login'] ) ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "���� 'Login' �������� ������������. ��������� � ���������." );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );
						exit;
					}

					if ( empty( $_POST['email'] ) ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "���� 'E-mail' �������� ������������. ��������� � ���������." );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );
						exit;
					}

					if ( empty( $_POST['p_1'] ) ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "���� ��� ������ �������� �������������. ��������� � ���������." );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );
						exit;
					}

					if ( $_POST['p_1'] != $_POST['p_2'] ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "��������� ������ �� ���������!" );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );
						exit;
					}
					Db::where( 'login', $_POST['login'] );
					$q = Db::getOne( TBL_USERS, 'login' );
					if ( count( $q ) > 0 ) {
						include( __DIR__ . "/inc/head.php" );
						$tpl->assign( "{ERROR}", "����� ������� ������ ��� ���������� � ���� ������." );
						$tpl->parse( "ERROR", "error" );
						$tpl->FastPrint( "ERROR" );
						exit;
					}
					$values = [
						"login" => $_POST['login'],
						"pass"  => md5( $_POST['p_1'] ),
						"email" => $_POST['email']
					];
					Db::insert( TBL_USERS, $values );
					header( "Location: " . $_SERVER['PHP_SELF'] . "?mode=" . $mode );
					exit;
				}
				break;

			default:
				include( __DIR__ . "/inc/head.php" );
				$tpl->assign( "{TITLE}", "- admin section - Users" );
				$tpl->parse( "HEAD", "head" );
				$tpl->FastPrint( "HEAD" );
			//	Db::check_connect();
				Db::orderBy( 'id', 'ASC' );
				$q = Db::get( TBL_USERS, NULL, [ 'id', 'login', 'email' ] );

				foreach ( $q as $row ) {
					$tpl->assign( "{ID}", $row['id'] );
					$tpl->assign( "{LOGIN}", $row['login'] );
					$tpl->assign( "{EMAIL}", $row['email'] );
					if ( $ses_id == '1' ) {
						$tpl->assign( "{NEW_LINK}", "| <a href='" . $_SERVER['PHP_SELF'] . "?mode=" . $mode . "&act=new'>�������� ������ ���������� </a>" );
						if ( $row['id'] != "1" ) {
							$tpl->assign( "{DEL_LINK}", "<a href='" . $_SERVER['PHP_SELF'] . "?mode=" . $mode . "&act=del&id=" . $row['id'] . "'>�������</a>" );
						} else {
							$tpl->assign( "{DEL_LINK}", "<a href='#'>��� ��������</a>" );
						}
					} else {
						$tpl->assign( "{NEW_LINK}", "" );
						$tpl->assign( "{DEL_LINK}", "�� �� ������ ����� �������<br> �������������" );
					}
					$tpl->assign( "{EDIT_LINK}", "<a href='" . $_SERVER['PHP_SELF'] . "?mode=" . $mode . "&act=edit&id=" . $row['id'] . "'>�������� ������</a>" );
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