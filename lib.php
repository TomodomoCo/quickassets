<?php

/**
 * Copyright (c) 2012 Chris Van Patten.
 */

class QuickAsset {

	function __construct() {

	}

	public function addShowMethod() {

	}

	public function addBustMethod() {

	}

	public function addAssetType() {

	}

	public function addHost() {

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


<h1>This does not work yet.</h1>
<?php echo $asset->url('img', 'image.png') ?>