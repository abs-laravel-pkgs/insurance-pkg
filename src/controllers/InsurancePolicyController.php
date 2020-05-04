<?php

namespace Abs\InsurancePkg;
use App\Http\Controllers\Controller;
use App\InsurancePolicy;
use Auth;
use Carbon\Carbon;
use DB;
use Entrust;
use Illuminate\Http\Request;
use Validator;
use Yajra\Datatables\Datatables;

class InsurancePolicyController extends Controller {

	public function __construct() {
		$this->data['theme'] = config('custom.theme');
	}

	public function getInsurancePolicyList(Request $request) {
		$insurance_policies = InsurancePolicy::withTrashed()

			->select([
				'insurance_policies.id',
				'insurance_policies.name',
				'insurance_policies.code',

				DB::raw('IF(insurance_policies.deleted_at IS NULL, "Active","Inactive") as status'),
			])
			->where('insurance_policies.company_id', Auth::user()->company_id)

			->where(function ($query) use ($request) {
				if (!empty($request->name)) {
					$query->where('insurance_policies.name', 'LIKE', '%' . $request->name . '%');
				}
			})
			->where(function ($query) use ($request) {
				if ($request->status == '1') {
					$query->whereNull('insurance_policies.deleted_at');
				} else if ($request->status == '0') {
					$query->whereNotNull('insurance_policies.deleted_at');
				}
			})
		;

		return Datatables::of($insurance_policies)
			->rawColumns(['name', 'action'])
			->addColumn('name', function ($insurance_policy) {
				$status = $insurance_policy->status == 'Active' ? 'green' : 'red';
				return '<span class="status-indicator ' . $status . '"></span>' . $insurance_policy->name;
			})
			->addColumn('action', function ($insurance_policy) {
				$img1 = asset('public/themes/' . $this->data['theme'] . '/img/content/table/edit-yellow.svg');
				$img1_active = asset('public/themes/' . $this->data['theme'] . '/img/content/table/edit-yellow-active.svg');
				$img_delete = asset('public/themes/' . $this->data['theme'] . '/img/content/table/delete-default.svg');
				$img_delete_active = asset('public/themes/' . $this->data['theme'] . '/img/content/table/delete-active.svg');
				$output = '';
				if (Entrust::can('edit-insurance_policy')) {
					$output .= '<a href="#!/insurance-pkg/insurance_policy/edit/' . $insurance_policy->id . '" id = "" title="Edit"><img src="' . $img1 . '" alt="Edit" class="img-responsive" onmouseover=this.src="' . $img1 . '" onmouseout=this.src="' . $img1 . '"></a>';
				}
				if (Entrust::can('delete-insurance_policy')) {
					$output .= '<a href="javascript:;" data-toggle="modal" data-target="#insurance_policy-delete-modal" onclick="angular.element(this).scope().deleteInsurancePolicy(' . $insurance_policy->id . ')" title="Delete"><img src="' . $img_delete . '" alt="Delete" class="img-responsive delete" onmouseover=this.src="' . $img_delete . '" onmouseout=this.src="' . $img_delete . '"></a>';
				}
				return $output;
			})
			->make(true);
	}

	public function getInsurancePolicyFormData(Request $request) {
		$id = $request->id;
		if (!$id) {
			$insurance_policy = new InsurancePolicy;
			$action = 'Add';
		} else {
			$insurance_policy = InsurancePolicy::withTrashed()->find($id);
			$action = 'Edit';
		}
		$this->data['success'] = true;
		$this->data['insurance_policy'] = $insurance_policy;
		$this->data['action'] = $action;
		return response()->json($this->data);
	}

	public function saveInsurancePolicy(Request $request) {
		// dd($request->all());
		try {
			$error_messages = [
				'code.required' => 'Short Name is Required',
				'code.unique' => 'Short Name is already taken',
				'code.min' => 'Short Name is Minimum 3 Charachers',
				'code.max' => 'Short Name is Maximum 32 Charachers',
				'name.required' => 'Name is Required',
				'name.unique' => 'Name is already taken',
				'name.min' => 'Name is Minimum 3 Charachers',
				'name.max' => 'Name is Maximum 191 Charachers',
			];
			$validator = Validator::make($request->all(), [
				'code' => [
					'required:true',
					'min:3',
					'max:32',
					'unique:insurance_policies,code,' . $request->id . ',id,company_id,' . Auth::user()->company_id,
				],
				'name' => [
					'required:true',
					'min:3',
					'max:191',
					'unique:insurance_policies,name,' . $request->id . ',id,company_id,' . Auth::user()->company_id,
				],
			], $error_messages);
			if ($validator->fails()) {
				return response()->json(['success' => false, 'errors' => $validator->errors()->all()]);
			}

			DB::beginTransaction();
			if (!$request->id) {
				$insurance_policy = new InsurancePolicy;
				$insurance_policy->company_id = Auth::user()->company_id;
			} else {
				$insurance_policy = InsurancePolicy::withTrashed()->find($request->id);
			}
			$insurance_policy->fill($request->all());
			if ($request->status == 'Inactive') {
				$insurance_policy->deleted_at = Carbon::now();
			} else {
				$insurance_policy->deleted_at = NULL;
			}
			$insurance_policy->save();

			DB::commit();
			if (!($request->id)) {
				return response()->json([
					'success' => true,
					'message' => 'Insurance Policy Added Successfully',
				]);
			} else {
				return response()->json([
					'success' => true,
					'message' => 'Insurance Policy Updated Successfully',
				]);
			}
		} catch (Exceprion $e) {
			DB::rollBack();
			return response()->json([
				'success' => false,
				'error' => $e->getMessage(),
			]);
		}
	}

	public function deleteInsurancePolicy(Request $request) {
		DB::beginTransaction();
		// dd($request->id);
		try {
			$insurance_policy = InsurancePolicy::withTrashed()->where('id', $request->id)->forceDelete();
			if ($insurance_policy) {
				DB::commit();
				return response()->json(['success' => true, 'message' => 'Insurance Policy Deleted Successfully']);
			}
		} catch (Exception $e) {
			DB::rollBack();
			return response()->json(['success' => false, 'errors' => ['Exception Error' => $e->getMessage()]]);
		}
	}

	public function getInsurancePolicys(Request $request) {
		$insurance_policies = InsurancePolicy::withTrashed()
			->with([
				'insurance-policies',
				'insurance-policies.user',
			])
			->select([
				'insurance_policies.id',
				'insurance_policies.name',
				'insurance_policies.code',
				DB::raw('IF(insurance_policies.deleted_at IS NULL, "Active","Inactive") as status'),
			])
			->where('insurance_policies.company_id', Auth::user()->company_id)
			->get();

		return response()->json([
			'success' => true,
			'insurance_policies' => $insurance_policies,
		]);
	}
}