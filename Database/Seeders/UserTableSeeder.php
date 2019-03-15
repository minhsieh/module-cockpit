<?php

namespace Modules\Cockpit\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Cockpit\Entities\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $user = User::firstOrNew([
            'email' => 'side112358@gmail.com',
        ]);

        $user->name = 'Min Hsieh';
        $user->password = bcrypt('123qwe123');
        $user->is_active = 1;
        $user->last_login = date("Y-m-d H:i:s");
        $user->save();

        if (app()->environment() == 'local') {
            $users = factory(User::class, 10)->create();
            foreach($users as $user){
                echo "User seed:\t".$user->name."\t create success!".PHP_EOL;
            }    
        }
    }
}
