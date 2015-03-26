<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 23.03.2015
 * Time: 15:16
 */
?>
<nav class="centered-navigation">
	<div class="centered-navigation-wrapper">
		<a href="/index.php" class="mobile-logo">
			<img src="/images/logo.png" alt="">
		</a>
		<a href="" class="centered-navigation-menu-button">MENU</a>
		<?
		if ( $razdel ) {
			?>
			<ul class="centered-navigation-menu"
				>
				<li <?= ( $razdel == '/index.php' ) ? 'class="nav-link current"' : 'class="nav-link"' ?>>
					<a href="/index.php">Главная</a></li
					>
				<li <?= ( $razdel == '/about.php' ) ? 'class="nav-link current"' : 'class="nav-link"' ?>>
					<a href="/about.php">Об&nbsp;&nbsp;авторе</a></li
					>
				<li <?= ( $razdel == '/portfolio.php' ) ? 'class="nav-link current"' : 'class="nav-link"' ?>>
					<a href="/portfolio.php">Портфолио</a></li
					>
				<li class="nav-link logo">
					<a href="/index.php" class="logo">
						<img src="/images/logo.png" alt="Логотип сайта">
					</a>
				</li
					>
				<li <?= ( $razdel == '/news.php' ) ? 'class="nav-link current"' : 'class="nav-link"'; ?>>
					<a href="/news.php">Новости</a></li
					>
				<li <?= ( $razdel == '/services.php' ) ? 'class="nav-link current"' : 'class="nav-link"'; ?>>
					<a href="/services.php">Услуги</a></li
					>
				<li <?= ( $razdel == '/comments.php' ) ? 'class="nav-link current"' : 'class="nav-link"'; ?>>
					<a href="/comments.php">Гостевая</a></li
					>
			</ul>
		<?
		}
		?>
	</div>
</nav>