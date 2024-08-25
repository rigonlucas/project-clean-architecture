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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('description', 500);
            $table->foreignId('created_by_user_id')->constrained('users');
            $table->foreignId('account_id')->constrained('accounts');
            $table->dateTime('start_at')->default(null)->nullable();
            $table->dateTime('finish_at')->default(null)->nullable();
            $table->smallInteger('status')->default(null)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid()->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
