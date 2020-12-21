<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_type_id')->constrained();
            $table->foreignId('validation_state_id')->constrained();
            $table->foreignId('service_id')->constrained();
            $table->foreignId('employee_id')->nullable()->constrained();
            $table->date('start');
            $table->date('finish')->nullable();
            $table->string('month_year_registry',7)->nullable();
            $table->string('path_data')->nullable();
            $table->text('observations')->nullable();
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
        Schema::dropIfExists('documents');
    }
}
