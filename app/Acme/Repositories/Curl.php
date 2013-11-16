<?php namespace Acme\Repositories;

use Illuminate\Support\Facades\Config;

class Curl {
	
	public function __construct() {

	}

	public function postToken($client_id, $client_secret, $redirect_uri, $code) {

		//set POST variables
		$url = Config::get('app.server_url').'access_token';
		$fields = array(
			'client_id='.$client_id,
			'client_secret='.$client_secret,
			'redirect_uri='.urlencode($redirect_uri),
			'code='.$code,
			'grant_type=authorization_code'
		);

		$fields_string = implode('&', $fields);
		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);	

		return $result;
	}	

}