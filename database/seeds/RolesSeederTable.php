<?php

use Illuminate\Database\Seeder;

class RolesSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	   DB::table("roles")->truncate();
       DB::table("roles")->insert([
			[
                "role_type_id" => 1,
				"status"=> 1,
                "role_privilege" => '{"manage_patient":1,"register_patient":1,"manage_department":1,"manage_hall":1,"summary_report":1,"manage_room":1,"patient_status":1,"manage_user":1,"manage_role":1,"manage_doctor":1, "doctor_dashboard":0,"dashboardData":1}',
				"created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ],
            [
                "role_type_id" => 2,
				"status"=> 1,
                "role_privilege" => '{"manage_patient":1,"register_patient":1,"manage_department":0,"manage_hall":0,"summary_report":0,"manage_room":0,"patient_status":0,"manage_user":0,"manage_role":0,"manage_doctor":0, "doctor_dashboard":0,"dashboardData":1}',
				"created_at"=> date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ],
            [
                "role_type_id" => 3,
				"status"=> 1,
                "role_privilege" => '{"manage_patient":0,"register_patient":0,"manage_department":0,"manage_hall":0,"summary_report":0,"manage_room":0,"patient_status":0,"manage_user":0,"manage_role":0,"manage_doctor":0, "doctor_dashboard":1,"dashboardData":0}',
				"created_at"=> date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ]

        ]);
    }
}
