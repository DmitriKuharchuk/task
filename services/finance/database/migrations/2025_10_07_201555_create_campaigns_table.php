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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->uuid('ad_group_id')->nullable();
            $table->string('campaign_type', 32);
            $table->string('status', 32)->default('active');
            $table->index('status', 'idx_campaign_status');
            $table->index('ad_group_id', 'idx_campaign_ad_group');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
