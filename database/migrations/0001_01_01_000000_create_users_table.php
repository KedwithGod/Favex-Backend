<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('first_name');
        $table->string('last_name');
        $table->string('username')->unique();
        $table->string('email')->unique();
        $table->string('phone')->unique();
        $table->string('where_heard')->nullable();
        $table->string('referral_tag')->nullable();

        // Auth credentials
        $table->string('password');        // bcrypt (salted by design)
        $table->string('txn_pin_hash')->nullable();    // hashed transaction pin

        // Biometric login token (nullable = not registered yet)
        $table->string('biometric_token')->nullable();

        $table->timestamp('email_verified_at')->nullable();
        $table->rememberToken();
        $table->timestamps();
    });
}

};
