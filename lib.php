<?php

/**
 * Copyright (c) 2012 Chris Van Patten.
 */

class QuickAsset {

	private $showMethods = array();
	private $bustMethods = array();
	private $assetTypes = array();
	private $hosts = array();

	public function __construct() {

	}

	public function addShowMethod($methodName, $function) {
	
		if (!in_array($methodName, array_keys($this->showMethods))
		{
			$this->showMethods[$methodName] = $function;
		}
		
	}

	public function addBustMethod($methodName, $function) {
		
		if (!in_array($methodName, array_keys($this->bustMethods))
		{
			$this->bustMethods[$methodName] = $function;
		}
				
	}

	public function addAssetType($assetType, $parameters) {

	}

	public function addHost($host, $parameters) {

	}

	public function url($assetType, $assetFile) {
		/*
		 * 1. Get $assetType
		 * 2. Determine which host should serve it
		 * 3. Determine which bustMethod and showMethod apply
		 * 4. Do magic
		 * 5. ...
		 * 6. Profit!
		 */
		 
		 /*
		 	In slightly more technical detail...
		 	
		 	Thinking...
		 	
		 	1. Get $assetType
		 	2. Go through class $hosts, find most specific match... or default
		 	3. Choose bustMethod from the assetType's $parameters... or default
		 	4. Choose showMethod from the assetType's $parameters... or default
		 	5. Execute host handler to pull a processed host string (rotation, etc.)
		 						(host handler uses $parameters to either return unfiltered,
		 						or swap '$' for host number based on internal counter and max.
		 						from $params)
		 	6. Execute bustMethod with $assetFile, $filteredHost. Return $bustedString
		 	7. Execute showMethod with $assetFile, $bustedString, $filteredHost. Return $assetURL.
		 	8. Echo $assetURL.
		 	
		 	
		 	Considering... does bustMethod need to know about the on-disk filename? Will this be a problem?
		 	
		 */
	}

}

?>