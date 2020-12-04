<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commercial_bussiness_id')->constrained();
            $table->string('rut', 16);
            $table->string('bussiness_name', 128);
            $table->string('address', 128);
            $table->string('phone1', 16);
            $table->string('phone2', 16)->nullable();
            $table->string('contact', 64);
            $table->string('email', 64)->nullable();
            $table->enum('affiliation', ['Mutual', 'ACHS', 'ASL', 'IST'])->nullable();
            $table->date('affiliation_date')->nullable();
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('companies');
    }
}
