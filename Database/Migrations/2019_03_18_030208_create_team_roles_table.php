<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permission_table = config('permission.table_names');

        Schema::create('team_roles', function (Blueprint $table) use($permission_table) {
            $table->integer( 'role_id' )->unique()->unsigned();
            $table->integer( 'team_id' )->index()->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_roles');
    }
}
