<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 19.04.2015
 * Time: 18:08
 */

defined( 'PROTECT_PAGE' ) or die( '������ ��������' );
header( 'Content-type: text/html; charset=windows-1251' );
?>
<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
	<title>���� ������������ ������</title>
	<link rel="shortcut icon" href="/images/favicon.png" />
	<link rel="stylesheet" href="/css/stop.css" media="screen" type="text/css" />
</head>

<body>
<div class="engineering">
	<div class="error">
		<div class="wrap">
    <pre><code>
            <span class="green">&lt;!</span><span>DOCTYPE html</span><span class="green">&gt;</span>
   <span class="orange">&lt;html&gt;</span>
	<span class="orange">&lt;style&gt;</span>
		* {
			              <span class="green">everything</span>:<span class="blue">awesome</span>;
		}
	<span class="orange">&lt;/style&gt;</span>
     <span class="orange">&lt;body&gt;</span>

		    �������� ���� ���������.
		    �� ����� "<?= $_SERVER['HTTP_HOST']?>" ���� ������������ ������.
		    � ��������� ����� ������ ����� ����� ��������������.

<span class="tehno-info">

<span class="orange">&nbsp;&lt;/body&gt;</span>
<span class="orange">&lt;/html&gt;</span>
</span>
		</code></pre>
		</div>
	</div>
</div>
</body>
<footer></footer>
</html>