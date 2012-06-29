<?php

/**
 * WARNING:
 * This will not work! Don't even bother trying!
 */


/**
 * Call QuickAsset into action
 */

include_once 'path/to/quickassets/lib.php';
$asset = new QuickAsset();


/**
 * 1. Define cache busters
 * -----------------------
 * Default "styles": inline, querystring, folder, and custom
 *
 * You define how the cache busting string itself is created, e.g.
 * if you want to link it to time/date stamps, deployment versions,
 * etc.
 *
 * The "custom" style allows you to determine not only how the
 * string is created, but where it's placed in the path.
 */

$asset->addCacheBuster('default', 'inline', function() {
	return 'VERSION';
});

$asset->addCacheBuster('random', 'querystring', function() {
	return rand(5, 500);
});

$asset->addCacheBuster('myfolder1', 'folder', function() {
	return 'VERSION';
});

$asset->addCacheBuster('myregex1', 'custom', function() {
	// Do your custom thing...
	$this->input = $output;

	return $output;
	// $output = 'path/to/movies/video.mp4';
	// Note the absence of a leading slash. If you set a relative
	// domain, we'll handle it.
});


/**
 * 2. Define asset types
 * ---------------------
 * QuickAssets is oblivious to your assets (bring your own caching);
 * this is only present so you can define paths to assets (DRY!) and
 * use different cache busting methods for each type of asset.
 */

$asset->addAssetType('img', array(
	assetPath = 'path/to/img/',
);

$asset->addAssetType('js', array(
	assetPath   = 'path/to/js/',
	cacheBuster = 'random',
);

$asset->addAssetType('css', array(
	assetPath   = 'path/to/css/',
	cacheBuster = 'myfolder1',
);

$asset->addAssetType('movie', array(
	assetPath   = 'path/to/movies/',
	cacheBuster = 'regex',
);


/**
 * 3. Define some domains
 * ----------------------
 * You can use a standard domain, a cycled domain (for better
 * parallelization of requests), or empty domains for
 * relative paths.
 *
 * Double backslash protocol-relative URLs are also supported
 * although they won't always work in local development.
 */

$asset->addDomain('//www.domain.com/', array(
	assetTypes = 'img',
));

$asset->addDomain('', array(
	assetTypes = 'js',
));

$asset->addDomain('http://media$.domain.com/', array(
	maxDomains = 4,
	assetTypes = 'css, movie',
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
<!-- /path/to/js/script.js?407 -->

<?= $asset->url('css', 'style.css') ?>
<!-- http://media1.domain.com/path/to/css/VERSION/style.css -->

<?= $asset->url('movie', 'video.mp4') ?>
<!-- http://media2.domain.com/path/to/movies/video.mp4 -->