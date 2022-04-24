<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->unique();
            $table->string('user_type')->nullable();
            $table->string('mobile');
            $table->string('dob');
            $table->string('otp')->nullable();
            $table->text('address');
            $table->timestamp('validity_date')->nullable();
            $table->unsignedBigInteger('created_by')->default(0);
            $table->boolean('is_verified')->default(0);
            $table->string('api_token')->nullable();
            $table->boolean('status')->default(1);
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
            $table->dropColumn('mobile');
            $table->dropColumn('dob');
            $table->dropColumn('address');
            $table->dropColumn('validity_date');
            $table->dropColumn('created_by');
            $table->dropColumn('is_verified');
            $table->dropColumn('api_token');
            $table->dropColumn('status');
        });
        Schema::enableForeignKeyConstraints();
    }
}
