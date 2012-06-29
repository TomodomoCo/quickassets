<?php

/**
 * Call QuickAsset into action
 */

$asset = new QuickAsset();


/**
 * Define some domains
 */

$asset->addDomain('http://www.domain.com/', array(
	type = 'img, js',
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

addCacheBuster('default', 'inline', function() {
	return 'VERSION';
});

addCacheBuster('random', 'querystring', function() {
	return rand(5, 500);
});


/**
 * Define asset types
 * QuickAssets is oblivious to your assets (bring your own caching);
 * this is only present so you can define paths to assets (DRY!) and
 * use different cache busting methods for each type of asset.
 */

addAssetType('img', array(
	path = 'path/to/img/',
);

addAssetType('js', array(
	path = 'path/to/js/',
	cachebuster = 'random'
);

addAssetType('css', array(
	path = 'path/to/css/',
);

?>

<h1>In practice</h1>

<?= $asset->url('img', 'image.png') ?>
<!-- http://www.domain.com/path/to/img/image.VERSION.png -->

<?= $asset->url('js', 'blah.js') ?>
<!-- http://www.domain.com/path/to/js/script.png?407 -->

<?= $asset->url('css', 'style.css') ?>
<!-- http://media1.domain.com/path/to/css/style.VERSION.css -->