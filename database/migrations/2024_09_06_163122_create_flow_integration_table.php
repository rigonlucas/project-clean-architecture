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
        Schema::create('flow_integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('flow_step_uuid')
                ->constrained('flow_steps', 'uuid')
                ->onDelete('cascade');
            $table->string('integration_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flow_integrations');
    }
};
