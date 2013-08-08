<?php

namespace eSpectacle\eSpectacleApi;

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

	public static function replaceObject($name, $id, $object)
	{
		$parseName = self::parseName($name, $id);
		
		if(!isset(self::$_library[$parseName])){
			$result = true;
		}else{
			$result = false;
		}
		self::$_library[$parseName] = $object;
		return $result;
	}
	
	public static function getObject($name, $id = false)
	{
		$parseName = self::parseName($name, $id);
		if(isset(self::$_library[$parseName])){
			return self::$_library[$parseName];
		}
		return false;
	}

	protected static function parseName($name, $id = false)
	{
		if(!$id){
			return $name;
		}
		switch(strtolower($name)){
			case 'organization':
			case 'production':
				$result = $id;
				break;
			default:
				$result = $name.'_'.$id;
				break;
		}
		return $result;
	}
}
