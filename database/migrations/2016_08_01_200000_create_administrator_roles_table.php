<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdministratorRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        //Roles table
        Schema::create('cmf_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        //Permissions table
        Schema::create('cmf_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->string('category')->nullable();
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });


        //Many to many administrator roles
        Schema::create('cmf_administrator_role', function (Blueprint $table) {
            $table->integer('administrator_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('administrator_id')->references('id')->on('cmf_administrators')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('cmf_roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['administrator_id', 'role_id']);
        });

        //Many to many role permissions
        Schema::create('cmf_permission_role', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')->references('id')->on('cmf_permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('cmf_roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cmf_permission_role');
        Schema::drop('cmf_administrator_role');
        Schema::drop('cmf_roles');
        Schema::drop('cmf_permissions');
    }
}
