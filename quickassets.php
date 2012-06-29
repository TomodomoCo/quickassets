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

$asset->addShowMethod('myCustomHandler', function() {
	$myAssetPath   = $this->assetPath();
	$myFilename    = $this->filename();
	$myBustMethod  = $this->bustMethod();

	return myAssetPath . str_replace('.', '.version-' . $myBustMethod . '.', $myFilename);
});


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

$asset->addBustMethod('default', function() {
	return 'VERSION';
});

$asset->addBustMethod('myRandomBuster', function() {
	return rand(5, 500);
});

$asset->addBustMethod('myCustomBuster', function() {
	return '123456';
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
	// bustMethod is not set, so it uses "default"
	// showMethod is not set, so it uses "inline" (also scoped to "default")
);

$asset->addAssetType('js', array(
	assetPath  = 'path/to/js/',
	bustMethod = 'myRandomBuster',
	showMethod = 'queryString',
);

$asset->addAssetType('css', array(
	assetPath  = 'path/to/css/',
	showMethod = 'folder',
);

$asset->addAssetType('movie', array(
	assetPath  = 'path/to/movies/',
	bustMethod = 'myCustomBuster',
	showMethod = 'myCustomHandler',
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
<!-- http://media2.domain.com/path/to/movies/video.version-123456.mp4 -->