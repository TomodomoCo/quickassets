<?php

/**
 * Copyright (c) 2012 Chris Van Patten.
 */

class QuickAsset {

	private $showMethods = array();
	private $bustMethods = array();
	private $assetTypes = array();
	private $hosts = array();
	private $assetTypesToHosts = array(); // more efficient lookup table

	public function __construct() {
	
		// prepare the default show methods and bust methods
		
		$this->addShowMethod('_default', function($host, $assetFile, $assetPath, $bustString) {
			
			// get the position of the last dot (the file extension)
			$lastDot = strrpos($assetFile, '.');
			
			// get the substring before the last dot (beginning) and after (end)
			$beginning = substr($assetFile, 0, $lastDot);
			$end = substr($assetFile, $lastDot, strlen($assetFile));
			
			// stitch together, putting the bust string in beteen $beginning and $end
			return $host . $assetPath . $beginning . '.' . $bustString . $end;			
			
		});
		
		$this->addBustMethod('_default', function($assetPath, $assetFile, $rootPath) {
			
			return date('YmdHis', @filemtime($rootPath . $assetPath . $assetFile));		
			
		});

	}

	public function addShowMethod($methodName, $function) {
	
		// sanity checking
		if (!is_string($methodName) || strlen($methodName) < 1)
		{
			trigger_error('QuickAsset::addShowMethod expects parameter 1 to be a non-empty string, ' . gettype($methodName) .' given', E_USER_WARNING);
			return false;
		}
		
		if (!is_callable($function))
		{
			trigger_error('QuickAsset::addShowMethod expects parameter 2 to be a callable function (anonymous function, string of callable function or array of strings to class/function)', E_USER_WARNING);
			return false;
		}
	
		// add to our internal store of show methods
		if (!in_array($methodName, array_keys($this->showMethods)))
		{
			$this->showMethods[$methodName] = $function;
			return true;
		}
		else {
			trigger_error('The show method name "' . htmlentities(strip_tags($methodName), ENT_QUOTES) . '" was already registered. Leaving the old one alone', E_USER_NOTICE);
			return false;
		}
		
	}

	public function addBustMethod($methodName, $function) {
		
		// sanity checking
		if (!is_string($methodName) || strlen($methodName) < 1)
		{
			trigger_error('QuickAsset::addBustMethod expects parameter 1 to be a non-empty string, ' . gettype($methodName) .' given', E_USER_WARNING);
			return false;
		}
		
		if (!is_callable($function))
		{
			trigger_error('QuickAsset::addBustMethod expects parameter 2 to be a callable function (anonymous function, string of callable function or array of strings to class/function)', E_USER_WARNING);
			return false;
		}
	
		// add to our internal store of bust methods
		if (!in_array($methodName, array_keys($this->bustMethods)))
		{
			$this->bustMethods[$methodName] = $function;
			return true;
		}
		else {
			trigger_error('The bust method name "' . htmlentities(strip_tags($methodName), ENT_QUOTES) . '" was already registered. Leaving the old one alone', E_USER_NOTICE);
			return false;
		}
				
	}

	public function addAssetType($assetType, $parameters) {
	
		// sanity checking
		if (!is_string($assetType) || strlen($assetType) < 1)
		{
			trigger_error('QuickAsset::addAssetType expects parameter 1 to be a non-empty string, ' . gettype($assetType) .' given', E_USER_WARNING);
			return false;
		}
			
		if (!is_array($parameters) || count($parameters) < 1)
		{
			trigger_error('QuickAsset::addAssetType expects parameter 2 to be a non-empty array, ' . gettype($parameters) . ' given.', E_USER_WARNING);
			return false;
		}
		
		if (!array_key_exists('assetPath', $parameters))
		{
			trigger_error('QuickAsset::addAssetType expects the $parameters array to include \'assetPath\'', E_USER_WARNING);
			return false;
		}
		
		if (strlen($parameters['assetPath']) < 1)
		{
			trigger_error('QuickAsset::addAssetType expects the \'assetPath\' parameter to not be empty', E_USER_WARNING);
			return false;			
		}
		
		// prepare the asset type for internal storage -- prepare custom callback parameters, or use defaults
		if (array_key_exists('bustMethod', $parameters))
		{
			$bustMethod = $parameters['bustMethod'];			
		}
		else {
			$bustMethod = '_default';
		}
		
		if (array_key_exists('showMethod', $parameters))
		{
			$showMethod = $parameters['showMethod'];
		}
		else {
			$showMethod = '_default';
		}
		
		if (array_key_exists('rootPath', $parameters))
		{
			$rootPath = $parameters['rootPath'];
		}
		else {
			$rootPath = dirname(realpath($_SERVER['SCRIPT_FILENAME'])) . '/';
		}
		
		// add the new asset type
		if (!in_array($assetType, $this->assetTypes))
		{
			$this->assetTypes[$assetType] = array(
				'assetPath'       =>     $parameters['assetPath'],
				'bustMethod'      =>     $bustMethod,
				'showMethod'      =>     $showMethod,
				'rootPath'        =>     $rootPath
			);
			
			return true;
		}
		else {
			trigger_error('The asset type "' . htmlentities(strip_tags($assetType), ENT_QUOTES) . '" was already registered. Leaving the old one alone', E_USER_NOTICE);
			return false;	
		}
		
	}

	public function addHost($host, $parameters) {
	
		// sanity checking
		if (!is_string($host))
		{
			trigger_error('QuickAsset::addHost expects parameter 1 to be a string, ' . gettype($host) .' given', E_USER_WARNING);
			return false;
		}
		
		if (strlen($host) < 1)
		{
			$host = '_'; // default host needs a default key
		}

		if (!is_array($parameters) || count($parameters) < 1)
		{
			trigger_error('QuickAsset::addHost expects parameter 2 to be a non-empty array, ' . gettype($parameters) . ' given.', E_USER_WARNING);
			return false;
		}
		
		if (!array_key_exists('assetTypes', $parameters))
		{
			trigger_error('QuickAsset::addHost expects the $parameters array to include \'assetTypes\'', E_USER_WARNING);
			return false;
		}
		
		if (strlen($parameters['assetTypes']) < 1)
		{
			trigger_error('QuickAsset::addHost expects the \'assetTypes\' parameter to not be empty', E_USER_WARNING);
			return false;
		}		
		
		// bring $assetTypes to bind this host to into an array
		$assetTypes = explode(',', str_replace(' ', '', $parameters['assetTypes']));
		
		if (count($assetTypes) < 1)
		{
			trigger_error('QuickAsset::addHost expects \'assetTypes\' to have at least one type defined', E_USER_WARNING);
			return false;			
		}
		
		// handle domain cycling
		
		//defaults
		$shouldCycleHosts = false;
		$maxHosts = 0;
		
		if (strpos($host, '$') !== false)
		{
			// check for 'maxHosts' parameter key
			
			if (!array_key_exists('maxHosts', $parameters))
			{
				trigger_error('Found a \'$\' in the hostname, but the \'maxHosts\' parameter was not set', E_USER_NOTICE);
			}
			else {
				$maxHosts = filter_var($parameters['maxHosts'], FILTER_VALIDATE_INT);
				
				if ($maxHosts === false)
				{
					trigger_error('QuickAsset::addHost expects the \'maxHosts\' parameter to be integer, '.gettype($parameters['maxHosts']).' given', E_USER_WARNING);
					$maxHosts = 0;
				}
				
				$shouldCycleHosts = true;				
			}
		}
		
		// add host to internal store
		if (!in_array($host, $this->hosts))
		{
			$this->hosts[$host] = array(
				'shouldCycleHosts'   =>      $shouldCycleHosts,
				'maxHosts'           =>      $maxHosts
			);
			
			// add to lookup table
			foreach ($assetTypes as $at)
			{
				if (array_key_exists($at, $this->assetTypesToHosts))
				{
					trigger_error('QuickAsset::addHost expects that only one host is bound to a an asset type (trying to assign "'.htmlentities(strip_tags($at), ENT_QUOTES).'" to two hosts)', E_USER_WARNING);
				}
				else {
					$this->assetTypesToHosts[$at] = $host;	
				}
			}
			
		}
		
		return true;

	}
	
	protected function cycleHosts($host)
	{
	
		if (!array_key_exists('hostCounter', $this->hosts[$host]))
		{
			$this->hosts[$host]['hostCounter'] = 0;
		}

		$cycledHost = str_replace('$', $this->hosts[$host]['hostCounter'], $host);
		if ( $this->hosts[$host]['hostCounter'] < ($this->hosts[$host]['maxHosts'] - 1) ) {
			++$this->hosts[$host]['hostCounter'];
		} else {
			$this->hosts[$host]['hostCounter'] = 0;
		}
		
		return $cycledHost;
		
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

		 // do we know about this asset type?		 
		 if (!array_key_exists($assetType, $this->assetTypes))
		 {
			 trigger_error('QuickAsset::url expects parameter 1 to be an $assetType that has already been registered', E_USER_WARNING);
			 return false;
		 }
		 
		 // has it been bound to a host?
		 if (!array_key_exists($assetType, $this->assetTypesToHosts))
		 {
			 trigger_error('QuickAsset::url expects parameter 1 to be an $assetType that has been bound to a host (please use QuickAsset::addHost)', E_USER_WARNING);
			 return false;
		 }
		 
		 $host = $this->assetTypesToHosts[$assetType];
		 
		  // is the bust method callable?
		 $bustMethod = $this->assetTypes[$assetType]['bustMethod'];
		 
		 if (!array_key_exists($bustMethod, $this->bustMethods))
		 {
			 trigger_error('QuickAsset::url expects the bustMethod "' . htmlentities(strip_tags($bustMethod), ENT_QUOTES) . '" to be registered before trying to use it', E_USER_WARNING);
			 return false;
		 }
		 
		 // is the show method callable?
		 $showMethod = $this->assetTypes[$assetType]['showMethod'];
		 
		 if (!array_key_exists($showMethod, $this->showMethods))
		 {
			 trigger_error('QuickAsset::url expects the showMethod "' . htmlentities(strip_tags($showMethod), ENT_QUOTES) . '" to be registered before trying to use it', E_USER_WARNING);
			 return false;
		 }
		 
		 // does the host exist?
		 if (!array_key_exists($host, $this->hosts))
		 {
			 trigger_error('QuickAsset::url expects the host "' . htmlentities(strip_tags($host), ENT_QUOTES) . '" to be registered before trying to use it', E_USER_WARNING);
			 return false;			 
		 }
		 
		 // cycle hosts if necessary
		 if ($this->hosts[$host]['shouldCycleHosts'])
		 {
			 $hostString = $this->cycleHosts($host);
		 }
		 else {
			 $hostString = $host;
		 }
		 
		 if ($hostString == '_')
		 {
		 	// deal with default host case
			$hostString = '';
		 }
		 
		 $assetPath = $this->assetTypes[$assetType]['assetPath'];
		 $rootPath = $this->assetTypes[$assetType]['rootPath'];
		 
		 // execute bust method to get bust string
		 $bustString = call_user_func_array($this->bustMethods[$bustMethod], array($assetPath, $assetFile, $rootPath));
		 
		 // execute show method to get finished string
		 $finishedAsset = call_user_func_array($this->showMethods[$showMethod], array($hostString, $assetFile, $assetPath, $bustString));
		 
		 // post filtering
		 //eesca
		 
		 // return/echo
		 return $finishedAsset;
	}

}

?>
