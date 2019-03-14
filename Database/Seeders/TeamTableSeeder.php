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

        if ($this->app->environment() == 'local') {
            $teams = [
                [
                    'tid' => 'main',
                    'name' => '主要測試團隊',
                    'is_active' => 1,
                    'contact' => 'Jeff Robin',
                    'tel' => '0933-222-111',
                    'email' => 'side112358@gmail.com',
                    'remark' => 'This is a test remark'
                ],
                [
                    'tid' => 'aaa',
                    'name' => '喔賣科技有限公司',
                    'is_active' => 1,
                    'contact' => '陳天才',
                    'tel' => '0932-344-344',
                    'email' => 'oimintw@gmail.com',
                    'remark' => 'This is a test remark'
                ],
                [
                    'tid' => 'bbb',
                    'name' => '超極股份有限公司',
                    'is_active' => 1,
                    'contact' => '王明達',
                    'tel' => '0922-222-333',
                    'email' => 'lostthing01@gmail.com',
                    'remark' => 'This is a test remark'
                ]
            ];
    
            foreach($teams as $team){
                $team_model = Team::firstOrNew(['tid' => $team['tid']]);
                $team_model->name = $team['name'];
                $team_model->is_active = $team['is_active'];
                $team_model->contact = $team['contact'];
                $team_model->tel = $team['tel'];
                $team_model->email = $team['email'];
                $team_model->remark = $team['remark'];
                $team_model->save();
            }
        }else{
            $team_model = Team::firstOrNew(['tid' => 'imin']);
            $team_model->name = "I'm In Studio";
            $team_model->is_active = true;
            $team_model->contact = "Min Hsieh";
            $team_model->email = "side112358@gmail.com";
            $team_model->save();
        }


    }
}
