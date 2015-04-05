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
			<ul class="centered-navigation-menu"
				>
				<li class="nav-link">
						<header>
							<h1>��������� ����<span id="profession">�������� � ������</span></h1>
						</header>
				</li
					>
				<li <?= ( $razdel == '/index.php' ) ? 'class="nav-link current"' : 'class="nav-link"' ?>>
					<a href="/index.php">�������</a></li
					>
				<li <?= ( $razdel == '/about.php' ) ? 'class="nav-link current"' : 'class="nav-link"' ?>>
					<a href="/about.php">��&nbsp;&nbsp;������</a></li
					>
				<li <?= ( $razdel == '/portfolio.php' ) ? 'class="nav-link current"' : 'class="nav-link"' ?>>
					<a href="/portfolio.php">���������</a></li
					>
				<li <?= ( $razdel == '/news.php' ) ? 'class="nav-link current"' : 'class="nav-link"'; ?>>
					<a href="/news.php">�������</a></li
					>
				<li <?= ( $razdel == '/services.php' ) ? 'class="nav-link current"' : 'class="nav-link"'; ?>>
					<a href="/services.php">������</a></li
					>
				<li <?= ( $razdel == '/comments.php' ) ? 'class="nav-link current"' : 'class="nav-link"'; ?>>
					<a href="/comments.php">��������</a></li
					>
			</ul>
		<?
		}
		?>
	</div>
</nav>