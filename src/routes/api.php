<?php
Route::group(['namespace' => 'Abs\InsurancePkg\Api', 'middleware' => ['api', 'auth:api']], function () {
	Route::group(['prefix' => 'api/insurance-pkg'], function () {
		//Route::post('punch/status', 'PunchController@status');
	});
});