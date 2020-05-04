<?php

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'insurance-pkg'], function () {

	
		//Insurance Policy
		Route::get('/insurance-policy/get-list', 'InsurancePolicyController@getInsurancePolicyList')->name('getInsurancePolicyList');
		Route::get('/insurance-policy/get-form-data', 'InsurancePolicyController@getInsurancePolicyFormData')->name('getInsurancePolicyFormData');
		Route::post('/insurance-policy/save', 'InsurancePolicyController@saveInsurancePolicy')->name('saveInsurancePolicy');
		Route::get('/insurance-policy/delete', 'InsurancePolicyController@deleteInsurancePolicy')->name('deleteInsurancePolicy');
		Route::get('/insurance-policy/get-filter-data', 'InsurancePolicyController@getInsurancePolicyFilterData')->name('getInsurancePolicyFilterData');

		

});