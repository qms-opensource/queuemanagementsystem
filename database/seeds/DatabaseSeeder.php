<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTypesSeederTable::class);
		$this->call(RolesSeederTable::class);
		$this->call(UserSeederTable::class);
    }
}
