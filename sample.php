<?php

/**
 * A _realistic_ sample configuration of quickassets.
 */

include_once 'lib.php';
$a = new QuickAsset();

$a->addAssetType('img', array(
	'assetPath' => 'wp-content/themes/awesomedesign/img/',
));

$a->addAssetType('js', array(
	'assetPath' => 'wp-content/themes/awesomedesign/js/',
));

$a->addAssetType('css', array(
	'assetPath' => 'wp-content/themes/awesomedesign/css/',
));

$a->addHost('//www.domain.com/', array(
	'assetTypes' => 'img, js',
));

if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
	$a->addHost('https://www.domain.com/', array(
		'assetTypes' => 'css',
	));
} else {
	$a->addHost('http://www.domain.com/', array(
		'assetTypes' => 'css',
	));
}

?>

<?= $a->url('img', 'image.png'); ?>
<!-- Outputs `//www.domain.com/wp-content/themes/awesomedesign/img/image.1341071509.png` -->

<?= $a->url('js', 'script.js'); ?>
<!-- Outputs `//www.domain.com/wp-content/themes/awesomedesign/js/script.1341071579.js` -->

<?= $a->url('css', 'style.css'); ?>
<!-- Outputs `http://www.domain.com/wp-content/themes/awesomedesign/js/style.1341071579.css` -->