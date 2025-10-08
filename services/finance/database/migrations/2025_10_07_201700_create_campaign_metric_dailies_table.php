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
        Schema::create('campaign_metric_dailies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->date('date');
            $table->decimal('cpa', 10, 4)->nullable();
            $table->decimal('ctr', 5, 4)->nullable();
            $table->decimal('conversion_rate', 5, 4)->nullable();
            $table->decimal('revenue_per_conversion', 10, 4)->nullable();
            $table->unsignedInteger('total_conversions')->default(0);
            $table->unsignedInteger('total_clicks')->default(0);
            $table->unsignedInteger('total_impressions')->default(0);
            $table->primary(['campaign_id','date']);
            $table->index(['date','campaign_id'], 'idx_metrics_date_campaign');
            $table->index('campaign_id', 'idx_metrics_campaign');
            $table->foreign('campaign_id')
                ->references('id')
                ->on('campaigns')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_metric_dailies');
    }
};
