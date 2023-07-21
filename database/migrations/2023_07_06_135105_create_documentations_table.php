<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_project_id');
            $table->string('file_name', 255);
            $table->string('file_size', 255);
            $table->string('file_path', 255);
            $table->string('created_by', 5);
            $table->string('updated_by', 5);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('employee_project_id')->references('id')->on('employee_project');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documentations');
    }
}