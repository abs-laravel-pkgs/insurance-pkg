@if(config('insurance-pkg.DEV'))
    <?php $insurance_pkg_prefix = '/packages/abs/insurance-pkg/src';?>
@else
    <?php $insurance_pkg_prefix = '';?>
@endif

<script type="text/javascript">
    var insurance_policies_voucher_list_template_url = "{{asset($insurance_pkg_prefix.'/public/themes/'.$theme.'/insurance-pkg/insurance-policy/insurance-policy.html')}}";
</script>
<script type="text/javascript" src="{{asset($insurance_pkg_prefix.'/public/themes/'.$theme.'/insurance-pkg/insurance-policy/controller.js')}}"></script>
