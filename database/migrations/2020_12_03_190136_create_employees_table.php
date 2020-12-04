<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_type_id')->constrained();
            $table->foreignId('commune_id')->constrained();
            $table->string('name', 32);
            $table->string('surname', 32);
            $table->string('second_surname', 32)->nullable();
            $table->date('birthday')->nullable();
            $table->string('address', 128)->nullable();
            $table->string('email', 64)->nullable();
            $table->string('phone',16)->nullable();
            $table->integer('gender');
            $table->string('nationality', 32)->nullable();
            $table->string('working_day', 32)->nullable()->default('45 horas');
            $table->string('disability', 20)->nullable();
            $table->integer('identification_type');
            $table->string('identification_id', 16);
            $table->integer('payment');
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
        Schema::dropIfExists('employees');
    }
}
