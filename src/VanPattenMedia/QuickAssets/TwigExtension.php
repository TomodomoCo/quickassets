<?php

namespace VanPattenMedia\QuickAssets;

/**
 * Copyright (c) 2012-2015 Van Patten Media Inc.
 */

class TwigExtension extends \Twig_Extension {

	protected $quickAsset;

	public function __construct( $quickAsset )
	{
		$this->quickAsset = $quickAsset;
	}

	public function getName()
	{
		return 'quickassets';
	}

	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction( 'asset_url', array( $this, 'assetUrl' ) )
		];
	}

	public function assetUrl( $type, $file )
	{
		return $this->quickAsset->url( $type, $file );
	}

}
