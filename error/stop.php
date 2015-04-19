<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 19.04.2015
 * Time: 18:08
 */

// defined( '_SECUR' ) or die( 'Доступ запрещен' );
?>
<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
	<title>Ошибка 404 - страница не найдена</title>
	<link rel="shortcut icon" href="/images/favicon.png" />
	<link rel="stylesheet" href="/css/404.css" media="screen" type="text/css" />
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


			                  На сайте <?= $_SERVER['HTTP_HOST']?> идут техничесские работы.
			                  В ближайшее время работа сайта будет восстановленна.

<span class="tehno-info">

<span class="orange">&nbsp;&lt;/body&gt;</span>
<span class="orange">&lt;/html&gt;</span>
</span>
		</code></pre>
		</div>
	</div>
</div>
</body>
</html>