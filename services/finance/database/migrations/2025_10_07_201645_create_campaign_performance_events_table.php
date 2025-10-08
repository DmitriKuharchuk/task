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
        Schema::create('campaign_performance_events', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('campaign_id');
            $table->string('event_type', 32);
            $table->timestampTz('event_time');
            $table->decimal('value', 10, 4)->nullable();
            $table->string('currency', 3)->nullable();
            $table->jsonb('metadata')->nullable();
            $table->index(['campaign_id','event_type'], 'idx_campaign_event');
            $table->index('event_time', 'idx_event_time');
            $table->index('event_type', 'idx_event_type');
            $table->foreign('campaign_id')
                ->references('id')
                ->on('campaigns')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_performance_events');
    }
};
