<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtpColumnsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('otp')->after('remember_token')->nullable();
            $table->timestamp('otp_generated_at')->after('otp')->nullable();
            $table->enum('is_otp_expired',['0','1'])->default('0')->after('otp_generated_at')->nullable();
        });
    }

    /**
     * Reverse the migratio
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
