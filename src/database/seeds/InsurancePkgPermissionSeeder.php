<?php
namespace Abs\InsurancePkg\Database\Seeds;

use App\Permission;
use Illuminate\Database\Seeder;

class InsurancePkgPermissionSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$permissions = [
			
			//Insurance Policies
			[
				'display_order' => 99,
				'parent' => null,
				'name' => 'insurance-policies',
				'display_name' => 'Insurance Policies',
			],
			[
				'display_order' => 1,
				'parent' => 'insurance-policies',
				'name' => 'add-insurance-policy',
				'display_name' => 'Add',
			],
			[
				'display_order' => 2,
				'parent' => 'insurance-policies',
				'name' => 'edit-insurance-policy',
				'display_name' => 'Edit',
			],
			[
				'display_order' => 3,
				'parent' => 'insurance-policies',
				'name' => 'delete-insurance-policy',
				'display_name' => 'Delete',
			],

			
		];
		Permission::createFromArrays($permissions);
	}
}