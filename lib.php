<?php

/**
 * Copyright (c) 2012 Chris Van Patten.
 */

class QuickAsset {

	function __construct() {

	}

	public function addShowMethod($methodName, $function) {

	}

	public function addBustMethod($methodName, $function) {

	}

	public function addAssetType($assetType, $parameters) {

	}

	public function addHost($host, $parameters) {

	}

	public function url($assetType, $assetFile) {
		/*
		 * 1. Get $assetType
		 * 2. Determine which domain should serve it
		 * 3. Determine which bustMethod and showMethod apply
		 * 4. Do magic
		 * 5. ...
		 * 6. Profit!
		 */
	}

}

/* This is not working code. You can't pass functions inline as arguments in PHP.
// Set default showMethod (inline)
$asset->addShowMethod('inline', function() {
	return $this->assetPath() . str_replace( '.', '.' . $this->bustMethod() . '.', $this->assetFile() );
});


// Set default bustMethod (modification time)
$asset->addBustMethod('default', function() {
	return filemtime( $this->domain . $this->assetPath . $this->assetFile );
});
*/

?>