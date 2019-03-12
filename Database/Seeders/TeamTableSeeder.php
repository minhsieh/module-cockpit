<?php

namespace Modules\Cockpit\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Cockpit\Entities\Team;

class TeamTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");

        Team::firstOrCreate([
            'tid' => 'main',
            'name' => '主要測試團隊',
            'is_active' => 1,
            'contact' => 'Jeff Robin',
            'tel' => '0933-222-111',
            'email' => 'side112358@gmail.com',
            'remark' => 'This is a test remark'
        ]);

        Team::firstOrCreate([
            'tid' => 'aaa',
            'name' => '喔賣科技有限公司',
            'is_active' => 1,
            'contact' => '陳天才',
            'tel' => '0932-344-344',
            'email' => 'oimintw@gmail.com',
            'remark' => 'This is a test remark'
        ]);

        Team::firstOrCreate([
            'tid' => 'bbb',
            'name' => '超極股份有限公司',
            'is_active' => 1,
            'contact' => '王明達',
            'tel' => '0922-222-333',
            'email' => 'lostthing01@gmail.com'
        ]);
    }
}
