<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageTestTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone_number');
            $table->string('link')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->default(0);
            $table->unsignedBigInteger('phone_id')->default(0);
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('company_link')->nullable();
            $table->timestamps();
        });

        Schema::create('phones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number');
            $table->string('user_name')->nullable();
            $table->string('company_name')->nullable();
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
