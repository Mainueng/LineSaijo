<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Facebook API Configuration
| -------------------------------------------------------------------
|
| To get an facebook app details you have to create a Facebook app
| at Facebook developers panel (https://developers.facebook.com)
|
|  facebook_app_id               string   Your Facebook App ID.
|  facebook_app_secret           string   Your Facebook App Secret.
|  facebook_login_redirect_url   string   URL to redirect back to after login. (do not include base URL)
|  facebook_logout_redirect_url  string   URL to redirect back to after logout. (do not include base URL)
|  facebook_login_type           string   Set login type. (web, js, canvas)
|  facebook_permissions          array    Your required permissions.
|  facebook_graph_version        string   Specify Facebook Graph version. Eg v3.2
|  facebook_auth_on_load         boolean  Set to TRUE to check for valid access token on every page load.
*/

/*if($_SERVER["SERVER_NAME"] == "localhost") {*/

	if ($_SESSION['app'] == 'club') {
		$config['facebook_app_id']                = '412653972769717';
		$config['facebook_app_secret']            = 'c1ca19387375f7ee72e2c352556394cc';
		$config['facebook_login_redirect_url']    = 'v1/technician/login_facebook';
		$config['facebook_logout_redirect_url']   = 'user_authentication/logout';
		$config['facebook_login_type']            = 'web';
		$config['facebook_permissions']           = array('email');
		$config['facebook_graph_version']         = 'v5.0';
		$config['facebook_auth_on_load']          = TRUE;
	} else {
		$config['facebook_app_id']                = '109089773122889';
		$config['facebook_app_secret']            = 'b1dbbefda6caa0bc1d83dfd871308639';
		$config['facebook_login_redirect_url']    = 'v1/auth/login_facebook';
		$config['facebook_logout_redirect_url']   = 'user_authentication/logout';
		$config['facebook_login_type']            = 'web';
		$config['facebook_permissions']           = array('email');
		$config['facebook_graph_version']         = 'v5.0';
		$config['facebook_auth_on_load']          = TRUE;
	}

	/*} else {*/
	/*$config['facebook_app_id']                = '1355919014458812';
	$config['facebook_app_secret']            = 'ac4909c31130c41ccc844d76945807d7';
	$config['facebook_login_redirect_url']    = 'v1/technician/login_facebook';
	$config['facebook_logout_redirect_url']   = 'user_authentication/logout';
	$config['facebook_login_type']            = 'web';
	$config['facebook_permissions']           = array('email');
	$config['facebook_graph_version']         = 'v3.3';
	$config['facebook_auth_on_load']          = TRUE;*/
/*}*/