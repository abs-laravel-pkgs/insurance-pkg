@if(config('insurance-pkg.DEV'))
    <?php $insurance_pkg_prefix = '/packages/abs/insurance-pkg/src';?>
@else
    <?php $insurance_pkg_prefix = '';?>
@endif


<script type='text/javascript'>
	app.config(['$routeProvider', function($routeProvider) {
	    $routeProvider.
	    //Insurance Policy
	    when('/insurance-pkg/insurance-policy/list', {
	        template: '<insurance-policy-list></insurance-policy-list>',
	        title: 'Insurance Policies',
	    }).
	    when('/insurance-pkg/insurance-policy/add', {
	        template: '<insurance-policy-form></insurance-policy-form>',
	        title: 'Add Insurance Policy',
	    }).
	    when('/insurance-pkg/insurance-policy/edit/:id', {
	        template: '<insurance-policy-form></insurance-policy-form>',
	        title: 'Edit Insurance Policy',
	    }).
	    when('/insurance-pkg/insurance-policy/card-list', {
	        template: '<insurance-policy-card-list></insurance-policy-card-list>',
	        title: 'Insurance Policy Card List',
	    });
	}]);

	//Insurance Policies
    var insurance_policy_list_template_url = '{{asset($insurance_pkg_prefix.'/public/themes/'.$theme.'/insurance-pkg/insurance-policy/list.html')}}';
    var insurance_policy_form_template_url = '{{asset($insurance_pkg_prefix.'/public/themes/'.$theme.'/insurance-pkg/insurance-policy/form.html')}}';
    var insurance_policy_card_list_template_url = '{{asset($insurance_pkg_prefix.'/public/themes/'.$theme.'/insurance-pkg/insurance-policy/card-list.html')}}';
    var insurance_policy_modal_form_template_url = '{{asset($insurance_pkg_prefix.'/public/themes/'.$theme.'/insurance-pkg/partials/insurance-policy-modal-form.html')}}';
</script>
<script type='text/javascript' src='{{asset($insurance_pkg_prefix.'/public/themes/'.$theme.'/insurance-pkg/insurance-policy/controller.js')}}'></script>

