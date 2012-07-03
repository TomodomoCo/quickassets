<?php

/**
 * WARNING:
 * This may not work! Try it only with the caveat that it may eat all your food and force you to walk the plank.
 */


/**
 * Call QuickAssets into action
 */

include_once '../lib.php';
$asset = new QuickAsset();


/**
 * 0. Define filename handlers
 * ---------------------------
 * We include three by default: inline/default, queryString, and folder
 *
 * Inline: file.ext > file.VERSION.ext OR file > file-VERSION
 * Querystring: file.ext > file.ext?VERSION
 * Folder: file.ext > VERSION/file.ext
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

$asset->addBustMethod('someString', function($assetPath, $assetFile, $rootPath) {
	return 'VERSION';
});

$asset->addBustMethod('myRandomBuster', function($assetPath, $assetFile, $rootPath) {
	return rand(5, 500);
});

$asset->addBustMethod('myCustomBuster', function($assetPath, $assetFile, $rootPath) {
	return '123456';
});

$asset->addBustMethod('md5Buster', function($assetPath, $assetFile, $rootPath) {
	return md5($rootPath . '/' . $assetPath . $assetFile);
});


/**
 * 2. Define asset types
 * ---------------------
 * QuickAssets is oblivious to your assets (bring your own caching);
 * this is only present so you can define paths to assets (DRY!) and
 * use different cache busting methods for each type of asset.
 */

$asset->addAssetType('img', array(
	'assetPath' => 'path/to/img/',
	// bustMethod is not set, so it uses "_default"
	// showMethod is not set, so it uses "_default"
));

$asset->addAssetType('js', array(
	'assetPath'  => 'path/to/js/',
	'bustMethod' => 'myRandomBuster',
));

$asset->addAssetType('css', array(
	'assetPath'  => 'path/to/css/',
));

$asset->addAssetType('movie', array(
	'assetPath'	=>	'path/to/movie',
	'bustMethod' => 'md5Buster'
));


/**
 * 3. Define some hosts
 * ----------------------
 * You can use a standard domain, a cycled domain (for better
 * parallelization of requests), or empty entry for relative
 * paths.
 *
 * Double backslash protocol-relative URLs are also supported
 * although they won't always work in local development.
 */

$asset->addHost('//www.domain.com/', array(
	'assetTypes' => 'img',
));

$asset->addHost('', array(
	'assetTypes' => 'js',
));

$asset->addHost('http://media$.domain.com/', array(
	'maxHosts' => 4,
	'assetTypes' => 'css, movie',
));
// Output (cycling through assets, evenly spread, in order):
// - http://media1.domain.com/
// - http://media2.domain.com/
// - http://media3.domain.com/
// - http://media4.domain.com/

?>


<h1>In practice</h1>

<?= $asset->url('img', 'image.png') ?>
<!-- //www.domain.com/path/to/img/image.VERSION.png -->

<?= $asset->url('js', 'script.js') ?>
<!-- /path/to/js/script.407.js -->

<?= $asset->url('css', 'style.css') ?>
<!-- http://media1.domain.com/path/to/css/VERSION/style.css -->

<?= $asset->url('movie', 'video.mp4') ?>
<!-- http://media2.domain.com/path/to/movies/video.version-8f5bffb4b330c96eb5733c2a76c6b364.mp4 -->