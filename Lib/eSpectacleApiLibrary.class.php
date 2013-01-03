<?php

class eSpectacleApiLibrary 
{
	private static $_instance = null;
	
	private static $_library = false;

	private function __construct() {
		self::$_library = array();
	}

	public static function getInstance() {
		if(is_null(self::$_instance)) {
			self::$_instance = new eSpectacleApiLibrary();
		}
		return self::$_instance;
	}
	
	public static function getLibrary()
	{
		return self::$_library;
	}
	
	public static function addObject($name, $id, $object)
	{
		$parseName = self::parseName($name, $id);
		if(!isset(self::$_library[$parseName])){
			self::$_library[$parseName] = $object;
			return true;
		}
		return false;
	}
	
	public static function getObject($name, $id)
	{
		$parseName = self::parseName($name, $id);
		if(isset(self::$_library[$parseName])){
			return self::$_library[$parseName];
		}
		return false;
	}

	protected static function parseName($name, $id)
	{
		return $name.'_'.$id;
	}
}
