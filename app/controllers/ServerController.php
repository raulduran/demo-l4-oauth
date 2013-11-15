<?php

class ServerController extends BaseController {

	public function __construct() {
		$this->beforeFilter('check-params', array('only' => array('getAuthorize', 'getSignin')));
		$this->beforeFilter('auth', array('only' => array('getAuthorize')));
		$this->beforeFilter('oauth', array('only' => array('getCheck')));
		$this->beforeFilter('csrf', array('only' => array('postAuthorize')));
		$this->beforeFilter('check-authorization-params', array('only' => array('getOAuth')));
	}

	public function getLogout($id) {
	    $result = DB::table('oauth_client_endpoints')
			->select(array('oauth_client_endpoints.redirect_uri as redirect_uri'))
			->where('oauth_client_endpoints.client_id', $id)
			->first();

		if (isset($result->redirect_uri)) {

			if (!Auth::guest()) {
				DB::table('oauth_sessions')
				->where('client_id', $id)
				->where('owner_id', Auth::user()->id)
				->delete();
				Auth::logout();				
			}

			return Redirect::to($result->redirect_uri);
		}
		else {
			return Redirect::to('/');
		}		
	}

	public function getOAuth() {
		return Redirect::to('/signin');		
	}

	public function getCheck() {
		return Response::json(array(
			'status' => 1,
			'message' => 'ok'
		));
	}

	public function getToken() {
		return AuthorizationServer::performAccessTokenFlow();		
	}

	public function getAuthorize() {
		// get the data from the check-authorization-params filter
		$params = Session::get('authorize-params');

		// get the user id
		$params['user_id'] = Auth::user()->id;

		if ($params['client_details']['auto']==1) {
			$code = AuthorizationServer::newAuthorizeRequest('user', $params['user_id'], $params);
			Session::forget('authorize-params');
			return Redirect::to(AuthorizationServer::makeRedirectWithCode($code, $params));
		}
		else {
			// display the authorization form
			return View::make('server.authorize', array('params' => $params));
		}
	}

	public function postAuthorize() {
		// get the data from the check-authorization-params filter
		$params = Session::get('authorize-params');

		// get the user id
		$params['user_id'] = Auth::user()->id;

		// check if the user approved or denied the authorization request
		if (Input::get('approve') !== null) {

			$code = AuthorizationServer::newAuthorizeRequest('user', $params['user_id'], $params);

			Session::forget('authorize-params');

			return Redirect::to(AuthorizationServer::makeRedirectWithCode($code, $params));
		}

		if (Input::get('deny') !== null) {

			Session::forget('authorize-params');

			return Redirect::to(AuthorizationServer::makeRedirectWithError($params));
		}
	}

	public function getSignin() {
		// Get the user's ID from their session
		$user_id = (Auth::guest())?null:Auth::user()->id;

		// User is signed in
		if ($user_id !== null) {
			// Redirect the user to /authorise route
			return Redirect::to('/authorize');
		}
		// User is not signed in, show the sign-in form
		else {
			return View::make('server.signin');
		}		
	}

	public function postSignin() {
		$message = null;

		// Process the sign-in form submission
		if (Input::get('signin') !== null) {

			try {
				//Login
				if (Auth::attempt(array('username'=>Input::get('username'), 'password'=>Input::get('password')))) {
					return Redirect::to('/authorize');
				}
				else { 
					$message = 'El nombre de usario y contraseña son incorrectos';
				}

			} 
			catch (Exception $e) {
				$message = $e->getMessage();
			}
		}

		return Redirect::to('/signin')->with('message', $message)->withInput();		
	}

}