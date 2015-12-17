<?php

use VanPattenMedia\QuickAssets\QuickAsset;

/**
 * WARNING:
 * This may not work! Try it only with the caveat that it may eat all your food and force you to walk the plank.
 */


/**
 * Call QuickAssets into action
 */
$asset = new QuickAsset();


/**
 * 0. Define filename handlers
 * ---------------------------
 * We include two by default: query string (default) and inline (in the file name)
 *
 * Query string (default): file.ext > file.ext?VERSION
 * Inline: file.ext > file.VERSION.ext
 *
 * You can add your own as well. We give you the relative path to
 * the asset type and the file name: you do the rest.
 */



/**
 * 1. Define cache busters
 * -----------------------
 * You define how the cache busting string itself is created, e.g.
 * if you want to link it to time/date stamps, deployment versions,
 * etc.
 *
 * The "custom" style allows you to determine not only how the
 * string is created, but where it's placed in the path.
 */

$asset->addBustMethod( 'someString', function( $assetPath, $assetFile, $rootPath ) {
	return 'VERSION';
} );

$asset->addBustMethod( 'myRandomBuster', function( $assetPath, $assetFile, $rootPath ) {
	return rand(5, 500);
} );

$asset->addBustMethod( 'myCustomBuster', function( $assetPath, $assetFile, $rootPath ) {
	return '123456';
} );

$asset->addBustMethod( 'md5Buster', function( $assetPath, $assetFile, $rootPath ) {
	return md5( $rootPath . '/' . $assetPath . $assetFile );
} );


/**
 * 2. Define asset types
 * ---------------------
 * QuickAssets is oblivious to your assets (bring your own caching);
 * this is only present so you can define paths to assets (DRY!) and
 * use different cache busting methods for each type of asset.
 */

$asset->addAssetType( 'img', [
	'assetPath' => 'path/to/img/',
	// bustMethod is not set, so it uses "_default"
	// showMethod is not set, so it uses "_default"
] );

$asset->addAssetType( 'js', [
	'assetPath'  => 'path/to/js/',
	'bustMethod' => 'myRandomBuster',
] );

$asset->addAssetType( 'css', [
	'assetPath'  => 'path/to/css/',
	'showMethod' => 'qa_inline',
] );

$asset->addAssetType( 'movie', [
	'assetPath'	=>	'path/to/movie/',
	'bustMethod' => 'md5Buster'
] );


/**
 * 3. Define some hosts
 * ----------------------
 * You can use a standard domain, a cycled domain (for better
 * parallelization of requests), or empty entry for relative
 * paths.
 *
 * In this step, you also bind asset types to the hosts you'd
 * like them paired with.
 */

$asset->addHost( '//www.domain.com/', [
	'assetTypes' => [ 'img' ],
] );

$asset->addHost( '', [
	'assetTypes' => [ 'js' ],
] );

$asset->addHost( 'https://media$.domain.com/', [
	'maxHosts'   => 4,
	'assetTypes' => [ 'css', 'movie' ],
] );

?>


<h1>In practice</h1>

<?= $asset->url( 'img', 'image.png'; ) ?>
<!-- //www.domain.com/path/to/img/image.png?20151217191924 -->

<?= $asset->url( 'js', 'script.js' ); ?>
<!-- /path/to/js/script.js?407 -->

<?= $asset->url( 'css', 'style.css' ); ?>
<!-- https://media2.domain.com/path/to/css/style.20151217191924.css -->

<?= $asset->url( 'movie', 'video.mp4' ); ?>
<!-- https://media2.domain.com/path/to/movies/video.mp4?8f5bffb4b330c96eb5733c2a76c6b364 -->
