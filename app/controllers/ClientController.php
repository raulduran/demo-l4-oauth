<?php

class ClientController extends BaseController {

	public function __construct() {
		//
	}

	public function getHome() {
		$result = @json_decode(file_get_contents(Config::get('app.server_url').'check?access_token='.Cookie::get('access_token')));

		$url = Request::url();
		$state = md5(uniqid(rand(), TRUE));
		$error = Session::get('error');

		switch ($url) {
			case 'http://app1.dev':
				$redirect = Config::get('app.server_url').'oauth?client_id=1&redirect_uri=http://app1.dev/login/1&response_type=code&scope=user&state='.$state;
				break;
			case 'http://app2.dev':
				$redirect = Config::get('app.server_url').'oauth?client_id=2&redirect_uri=http://app2.dev/login/2&response_type=code&scope=user&state='.$state;
				break;
			case 'http://app3.dev':
				//No redirect start
				$redirect = null;
				break;
			default:
				$redirect = null;
				break;
		}

		//If button click denied no auto-redirect
		if ($error=='access_denied') $redirect = null;

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

		return Redirect::to('/')->with(Input::all());
	}

}