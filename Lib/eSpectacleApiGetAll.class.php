<?php
/*
 * This file is part of the e-Spectacles API package.
 * (c) 2011-2012 Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//include_once 'eSpectacleApiData.class.php';

/**
 * eSpectaclesApiGetAll offers convenience methods to call the eSpectacles API (function GET_ALL) :
 * - generates HTML request
 * - parses the response
 * - manages a cache process (requires a directory with write permission)
 * - offers easy access to the XML fields
 * 
 * @package	eSpectaclesApi
 * @author	Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 */
namespace eSpectacle\eSpectacleApi;

class eSpectacleApiGetAll {
	public $key;

	public $objects	= array();
	public $errors 	= array();

	/**
	 * Constructor
	 * @param string $object	e-Spectacles API key
	 * @param string $object	object's name
	 * @param integer $id		object's unique id
	 * @return void
	 */
	public function __construct($key) {
		$this->key = $key;
	}

	public function getErrors() {
		return $this->errors;
	}

	public function getObjects(){
		return $this->objects;
	}
	
	/**
	 * Whether or not the server sent errors
	 * @param unknown_type $xml
	 * @return boolean
	 * 
	 */
	public function hasErrors($xml) {
		$dom = new \DomDocument();
		$dom->loadXml($xml, LIBXML_NOBLANKS);

		$xPath = new \DOMXPath($dom);
		$errors = $xPath->query('//errors');
		if ($errors->length) {
			foreach ($errors as $error) {
				$this->errors[$error->getAttribute('id')] = $error->nodeValue;
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Loads the object from e-Spectacle API. 
	 * When cache process is activated, checks if cache file exists and return its content if is not yet expired.
	 * @param boolean $force	Bypass the cache process when $force is true
	 * @return string			Returns the XML file's content as a string
	 */
	public function load($force = false) {
		$url = 'http://sf.e-spectacles.fr/api.php/webservice/getAll?key='. $this->key;

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		//TODO remove the next line with basic authentication
		curl_setopt($curl, CURLOPT_USERPWD, "guest:123456");

		$xml = curl_exec($curl);
		curl_close($curl);

		if (!$xml) {
			return false;
		}

		if ($this->hasErrors($xml)) {
			return false;
		}

		$dom = new \DOMDocument();
		print_r('create dom - ');
		$dom->loadXML($xml);
		print_r('dom loaded');
		
		foreach($dom->documentElement->childNodes as $node){
			if($node->nodeType == XML_ELEMENT_NODE){
				print_r($node->nodeName.' - ');
				$this->objects[] = new eSpectacleApiExternal($node, $dom);
			}
		}
		return $this->objects;
		//$this->datas = new eSpectacleApiData($xml);
		//return $this->datas->load();
	}

}
