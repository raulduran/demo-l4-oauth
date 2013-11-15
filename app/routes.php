<?php

//DEMO CLIENT//

Route::get('/', function()
{
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
});

Route::get('/login/{id}', function($id)
{
	$curl = new Acme\Repository\Curl();

	$result = json_decode($curl->getTokenBinter($id, 1234, 'http://app'.$id.'.dev/login/'.$id, Input::get('code')));

	if (isset($result->access_token)) {
		Cookie::queue('access_token', $result->access_token, $result->expires_in/60);
	}
	else {
		Cookie::queue('access_token', null);
	}

	return Redirect::to('/');	
});

//SERVER

Route::get('/logout/{id}', function($id)
{
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
});

Route::get('/oauth', array('before' => 'check-authorization-params', function()
{
	return Redirect::to('/signin');
}));

Route::get('/signin', array('before' => 'check-params', function()
{
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
}));

Route::get('check', array('before' => 'oauth', function(){
	return Response::json(array(
		'status' => 1,
		'message' => 'ok'
	));
}));

Route::post('/signin', function()
{
	$message = 'OK';

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

		} catch (Exception $e) {
			$message = $e->getMessage();
		}
	}

	return Redirect::to('/signin')->with('message', $message)->withInput();
});


Route::post('/access_token', function()
{
	return AuthorizationServer::performAccessTokenFlow();
});

Route::get('/authorize', array('before' => 'check-params|auth', function()
{
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
}));

Route::post('/authorize', array('before' => 'check-params|auth|csrf', function()
{
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
}));