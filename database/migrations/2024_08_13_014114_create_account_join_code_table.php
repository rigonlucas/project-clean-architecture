<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('account_join_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 8);
            $table->foreignId('account_id')->constrained('accounts');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->dateTime('expired_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_join_codes');
    }
};
