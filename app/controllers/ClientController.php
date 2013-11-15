<?php

class ClientController extends BaseController {

	public function __construct() {
		//
	}

	public function getHome() {
		$result = @json_decode(file_get_contents('http://oauth.bintercanarias.dev/check?access_token='.Cookie::get('access_token')));

		$url = Request::url();
		$state = md5(uniqid(rand(), TRUE));

		switch ($url) {
			case 'http://app1.dev':
				$redirect = 'http://oauth.bintercanarias.dev/oauth?client_id=1&redirect_uri=http://app1.dev/login/1&response_type=code&scope=user&state='.$state;
				break;
			case 'http://app2.dev':
				$redirect = 'http://oauth.bintercanarias.dev/oauth?client_id=2&redirect_uri=http://app2.dev/login/2&response_type=code&scope=user&state='.$state;
				break;
			case 'http://app3.dev':
				$redirect = 'http://oauth.bintercanarias.dev/oauth?client_id=3&redirect_uri=http://app3.dev/login/3&response_type=code&scope=user&state='.$state;
				break;
			default:
				$redirect = null;
				break;
		}	

		if (isset($result->status) && $result->status==1) {
			return View::make('client.home', array("url"=>$url));
		}
		else {
			if ($redirect) {
				return Redirect::to($redirect);
			}
			else {
				return View::make('client.login', array("url"=>$url, "state"=>$state));
			}
		}
	}

	public function getLogin($id) {
		$curl = new Acme\Repositories\Curl();

		$result = json_decode($curl->postToken($id, 1234, 'http://app'.$id.'.dev/login/'.$id, Input::get('code')));

		if (isset($result->access_token)) {
			Cookie::queue('access_token', $result->access_token, $result->expires_in/60);
		}
		else {
			Cookie::queue('access_token', null);
		}

		return Redirect::to('/');
	}

}