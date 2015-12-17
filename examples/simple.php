<?php

use VanPattenMedia\QuickAssets\QuickAsset;

/**
 * A simple sample configuration of QuickAssets.
 */

$a = new QuickAsset();

// Asset types
$a->addAssetType( 'img', [
	'assetPath' => 'wp-content/themes/awesomedesign/img/',
] );

$a->addAssetType( 'js', [
	'assetPath'  => 'wp-content/themes/awesomedesign/js/',
	'showMethod' => 'qa_inline',
] );

$a->addAssetType('css', array(
	'assetPath' => 'wp-content/themes/awesomedesign/css/',
] );

// Asset hosts
$a->addHost('//www.domain.com/', array(
	'assetTypes' => [ 'img', 'js' ],
] );

if ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443 ) {
	$a->addHost( 'https://www.domain.com/', [
		'assetTypes' => [ 'css' ],
	] );
} else {
	$a->addHost( 'http://www.domain.com/', [
		'assetTypes' => [ 'css' ],
	] );
}

?>


<h1>In practice</h1>

<?= $a->url( 'img', 'image.png' ); ?>
<!-- Outputs `//www.domain.com/wp-content/themes/awesomedesign/img/image.png?20151217191924` -->

<?= $a->url( 'js', 'script.js' ); ?>
<!-- Outputs `//www.domain.com/wp-content/themes/awesomedesign/js/script.20151217191924.js` -->

<?= $a->url( 'css', 'style.css' ); ?>
<!-- Outputs `https://www.domain.com/wp-content/themes/awesomedesign/js/style.css?20151217191924` -->
