<?php

/**
 * Call QuickAsset into action
 */

$asset = new QuickAsset();


/**
 * Define some domains
 */

$asset->addDomain('http://www.domain.com/', array(
	type = 'img',
));

// Adding an empty domain seems easier and more explicit for relative
// URLs than adding ANOTHER setting
$asset->addDomain('', array(
	type = 'js',
));

$asset->addDomain('http://media$.domain.com/', array(
	max = 4,
	type = 'css',
));
// Output (cycling through assets, evenly spread, in order):
// - http://media1.domain.com/
// - http://media2.domain.com/
// - http://media3.domain.com/
// - http://media4.domain.com/


/**
 * Define cache busters
 * Three default "styles": inline, querystring, and folder
 * You define how the cache busting string itself is created, e.g.
 * if you want to link it to time/date stamps, deployment versions,
 * etc. Full control.
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
});


/**
 * Define asset types
 * QuickAssets is oblivious to your assets (bring your own caching);
 * this is only present so you can define paths to assets (DRY!) and
 * use different cache busting methods for each type of asset.
 */

$asset->addAssetType('img', array(
	path = 'path/to/img/',
);

$asset->addAssetType('js', array(
	path = 'path/to/js/',
	cachebuster = 'random',
);

$asset->addAssetType('css', array(
	path = 'path/to/css/',
	cachebuster = 'myfolder1',
);

$asset->addAssetType('movie', array(
	path = 'path/to/movies/',
	cachebuster = 'regex',
);

?>

<h1>In practice</h1>

<?= $asset->url('img', 'image.png') ?>
<!-- http://www.domain.com/path/to/img/image.VERSION.png -->

<?= $asset->url('js', 'blah.js') ?>
<!-- http://www.domain.com/path/to/js/script.png?407 -->

<?= $asset->url('css', 'style.css') ?>
<!-- http://media1.domain.com/path/to/css/VERSION/style.css -->