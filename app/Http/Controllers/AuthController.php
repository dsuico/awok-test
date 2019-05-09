<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
	public function login(Request $request) {
		$http = new \GuzzleHttp\Client();

		try {
			$response = $http->post('http://homestead.test/oauth/token', [
				'form_params' => [
					'grant_type' => 'password',
					'client_id' => 2,
					'client_secret' => 'DV0meiwjY1w4sV4qX6oBEs34QGzRsT3XV6NcOpOb',
					'email' => $request->username,
					'password' => $request->password
				]
			]);
			return $response->getBody();

		} catch (\GuzzleHttp\Exception\BadResponseException $e) {

			if ($e->getCode() == 400) {
				return response()->json('Invalid Request', $e->getCode());
			} else if ($e->GetCode() == 401) {
				return response()->json('Incorrect Credentials', $e->getCode());
			}

			return response()->json('Something went wrong on the server.', $e->getCode());
		}
	}
}

