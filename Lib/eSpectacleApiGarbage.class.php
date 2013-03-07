<?php

namespace eSpectacle\eSpectacleApi;

class eSpectacleApiGarbage extends eSpectacleApiElement
{

	public function __call($name, $args)
	{
		return $this;
	}
	
	public function __toString()
	{
		return '';
	}
	
	protected function load() {}
	
	public function isLoaded()
	{
		return false;
	}

}