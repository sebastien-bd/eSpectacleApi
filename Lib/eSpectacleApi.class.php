<?php
namespace eSpectacle\eSpectacleApi;

/**
 * eSpectaclesApi offers convenience methods to call the eSpectacles API :
 * - Authentication
 * - Request builder
 *
 * @package	eSpectaclesApi
 * @author	Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 */

class eSpectacleApi 
{
	protected $privateHash;
	protected $publicHash;
	protected $host	= "http://api.e-spectacle.fr/";
	
	public function __construct($privateHash, $publicHash, $host = false)
	{
		$this->privateHash = $privateHash;
		$this->publicHash = $publicHash;
		if($host){
			$this->host = $host;
		}
	}
	
	public function getHost()
	{
		return $this->host;
	}
	
	public function generateRequest($parameters)
	{
		$parameters['hash'] = $this->publicHash;
		ksort($parameters);
		$hash = hash_hmac('sha256', serialize($parameters), $this->privateHash);
		return array_merge($parameters, array('hmac' => $hash));
	}
	
	public function sendRequest($parameters)
	{
		$request = $this->generateRequest($parameters);
		
		$module = $request['module'];
		$action = $request['action'];
		unset(
				$request['module'],
				$request['action']
		);
		
		$url = $this->host.$module.'/'.$action;
		$queryString = array();
		foreach($request as $key=>$value){
			$queryString[] = $key.'='.$value;
		}
		if($queryString){
			$url .= '?'.implode('&', $queryString);
		}

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		
		$response = curl_exec($curl);
		curl_close($curl);
		if(!$response){
			return false;
		}else{
			return $response;
		}
	}
}
