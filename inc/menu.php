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
			<img src="/images/mobile-logo.png" alt="mobile-logo">
		</a>
		<a href="" class="centered-navigation-menu-button">
			<span class="mobile-menu">MENU</span>
		</a>

		<?
		if ( $razdel ) {
			?>
			<header>
			<ul class="centered-navigation-menu"
				>
				<li class="nav-link">

							<h1>Алексеева Анна<span id="profession">фотограф в Одессе</span></h1>
				</li
					>
				<li <?= ( $razdel == '/index.php' ) ? 'class="nav-link current"' : 'class="nav-link"' ?>>
					<a href="/index">Главная</a></li
					>
				<li <?= ( $razdel == '/about.php' ) ? 'class="nav-link current"' : 'class="nav-link"' ?>>
					<a href="/about">Об&nbsp;&nbsp;авторе</a></li
					>
				<li <?= ( $razdel == '/portfolio.php' ) ? 'class="nav-link current"' : 'class="nav-link"' ?>>
					<a href="/portfolio">Портфолио</a></li
					>
				<li <?= ( $razdel == '/news.php' ) ? 'class="nav-link current"' : 'class="nav-link"'; ?>>
					<a href="/news">Новости</a></li
					>
				<li <?= ( $razdel == '/services.php' ) ? 'class="nav-link current"' : 'class="nav-link"'; ?>>
					<a href="/services">Услуги</a></li
					>
				<li <?= ( $razdel == '/comments.php' ) ? 'class="nav-link current"' : 'class="nav-link"'; ?>>
					<a href="/comments">Гостевая</a></li
					>
			</ul>
			</header>
		<?
		}
		?>
	</div>
</nav>