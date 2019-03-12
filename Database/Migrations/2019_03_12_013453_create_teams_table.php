<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tid')->index()->comment('代碼');
            $table->string('name')->comment('名稱');
            $table->boolean('is_active')->comment('是否啟用');
            $table->string('contact')->nullable()->comment('聯絡人');
            $table->string('tel')->nullable()->comment('聯絡電話');
            $table->string('email')->nullable()->comment('連絡信箱');
            $table->text('remark')->nullable()->comment('備註');
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
        Schema::dropIfExists('teams');
    }
}
