<?
use proxy\Db as Db;

require(__DIR__ . '/src/config/config.php'); // ����� ������, ����������, ����������� ����, ���������� ������, ���� �������

$tpl = new Comments\Template("classes/Comments/_templates/");
$tpl->define( [
	"head"  => "head.tpl",
	"index" => "adm_auth.tpl",
	"error" => "error.tpl",
	"foot"  => "foot.tpl",
] );
$tpl->assign( "{SCRIPT}", isset( $_SERVER['PHP_SELF'] ) ? $_SERVER['PHP_SELF'] : NULL );
$tpl->assign( "{ADM_MENU}", "" );
$start = $tpl->utime();


IF ( !isset( $_SESSION['logged'] )  || $_SESSION['logged'] !== TRUE ):
	if ( !isset( $_POST['submit'] ) ) {
		include( __DIR__ . "/inc/head.php" );
		$tpl->assign( "{TITLE}", "- �����������" );
		$tpl->parse( "HEAD", "head" );
		$tpl->parse( "INDEX", "index" );
		$tpl->FastPrint( "HEAD" );
		$tpl->FastPrint( "INDEX" );
	} else {

		if ( empty( $_POST['login'] ) || $_POST['login'] == 'login' ) {
			include( __DIR__ . "/inc/head.php" );
			$tpl->assign( "{ERROR}", "���� 'Login' �������� ������������ ��� ����������." );
			$tpl->parse( "ERROR", "error" );
			$tpl->FastPrint( "ERROR" );
		} else if ( empty( $_POST['pass'] ) || $_POST['pass'] == 'pass' ) {
			include( __DIR__ . "/inc/head.php" );
			$tpl->assign( "{ERROR}", "��� ����� ���������� ��������� � ��������� ���� 'Password'." );
			$tpl->parse( "ERROR", "error" );
			$tpl->FastPrint( "ERROR" );
		} else {
			Db::where("login", $_POST['login']);
			$q = Db::get(TBL_USERS, NULL, [ 'id','login','pass' ] );

			if ( count( $q ) > '0' ) {
				$_pass = $_id = $_login = NULL;
				extract($q[0], EXTR_PREFIX_ALL, "");

				if ( md5( $_POST['pass'] ) === $_pass ) {
					$_SESSION['logged'] = TRUE;
					$_SESSION['id']     = $_id;
					$_COOKIE['nick']    = $_login;
					if($_id == 1) {$_SESSION['admnews'] = md5($_login.'///'.$_pass);}
					$_SESSION['nick']   = $_login;
					main_redir( 'admin.php' );
				} else {
					include( __DIR__ . "/inc/head.php" );
					$tpl->assign( "{ERROR}", "�������� ����� � ������!" );
					$tpl->parse( "ERROR", "error" );
					$tpl->FastPrint( "ERROR" );
				}
			} else {
				include( __DIR__ . "/inc/head.php" );
				$tpl->assign( "{ERROR}", "�������� ����� � ������!" );
				$tpl->parse( "ERROR", "error" );
				$tpl->FastPrint( 'ERROR' );
			}


		}
	}

	$end = $tpl->utime();
	$tpl->assign( "{GEN_TIME}", sprintf( '%01.3f ', ( $end - $start ) ) );
	$tpl->parse( 'FOOT', 'foot' );
	$tpl->FastPrint( 'FOOT' );

 	include( __DIR__ . '/inc/footer.php' );
ELSE:
	include(ROOT_PATH . 'func.php');
	main_redir( 'admin.php' );
ENDIF;