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
    Schema::create('otps', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
        $table->string('purpose'); // e.g., 'password_reset'
        $table->string('code_hash'); // store hashed OTP, never raw
        $table->timestamp('expires_at');
        $table->timestamp('consumed_at')->nullable();
        $table->string('email')->nullable(); // store email if user not yet created
        $table->timestamps();

        $table->index(['user_id', 'purpose']);
    });
}

};
