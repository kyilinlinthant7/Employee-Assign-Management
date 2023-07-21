<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeProgrammingLanguageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_programming_language', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('programming_language_id');
            $table->string('created_by', 5);
            $table->string('updated_by', 5);
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('programming_language_id')->references('id')->on('programming_languages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_programming_language');
    }
}