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

							<h1>��������� ����<span id="profession">�������� � ������</span></h1>
				</li
					>
				<li <?= ( $razdel == '/index' ) ? 'class="nav-link current"' : 'class="nav-link"' ?>>
					<a href="/index">�������</a></li
					>
				<li <?= ( $razdel == '/about' ) ? 'class="nav-link current"' : 'class="nav-link"' ?>>
					<a href="/about">��&nbsp;&nbsp;������</a></li
					>
				<li <?= ( $razdel == '/portfolio' ) ? 'class="nav-link current"' : 'class="nav-link"' ?>>
					<a href="/portfolio">���������</a></li
					>
				<li <?= ( $razdel == '/news' ) ? 'class="nav-link current"' : 'class="nav-link"'; ?>>
					<a href="/news">�������</a></li
					>
				<li <?= ( $razdel == '/services' ) ? 'class="nav-link current"' : 'class="nav-link"'; ?>>
					<a href="/services">������</a></li
					>
				<li <?= ( $razdel == '/comments' ) ? 'class="nav-link current"' : 'class="nav-link"'; ?>>
					<a href="/comments">��������</a></li
					>
			</ul>
			</header>
		<?
		}
		?>
	</div>
</nav>