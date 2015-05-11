<?php
/**
 * Created by PhpStorm.
 * User: Jurii
 * Date: 21.07.14
 * Time: 17:15
 */
defined('_SECUR') or die('Доступ запрещен');

$sitemap = new MapGenerator( "http://" . $_SERVER['HTTP_HOST'] . "/" );

// add urls
$sitemap->addUrl( "http://" . $_SERVER['HTTP_HOST'], date( 'c' ), 'daily', '1' );
$sitemap->addUrl( "http://" . $_SERVER['HTTP_HOST'] . "/index.php", date( 'c' ), 'daily', '0.8' );
$sitemap->addUrl( "http://" . $_SERVER['HTTP_HOST'] . "/about.php", date( 'c' ), 'daily', '0.8' );
$sitemap->addUrl( "http://" . $_SERVER['HTTP_HOST'] . "/portfolio.php", date( 'c' ), 'daily', '0.8' );
$sitemap->addUrl( "http://" . $_SERVER['HTTP_HOST'] . "/services.php", date( 'c' ), 'daily', '0.8' );
$sitemap->addUrl( "http://" . $_SERVER['HTTP_HOST'] . "/news.php", date( 'c' ), 'daily', '0.8' );
$sitemap->addUrl( "http://" . $_SERVER['HTTP_HOST'] . "/comments.php", date( 'c' ), 'daily', '0.8' );

// will create also compressed (gzipped) sitemap
$sitemap->createGZipFile = true;

// create sitemap
$sitemap->createSitemap();

// write sitemap as file
$sitemap->writeSitemap();

// update robots.txt file
$sitemap->updateRobots();

if(!DEBUG_MODE) {
// submit sitemaps to search engines
$submit = $sitemap->submitSitemap();
// Error::var_dump('submit');
}