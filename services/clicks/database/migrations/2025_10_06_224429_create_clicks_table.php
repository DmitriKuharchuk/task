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
        Schema::create('clicks', function (Blueprint $table) {
            $table->id();
            $table->string('click_id', 128)->unique();
            $table->unsignedBigInteger('offer_id');
            $table->string('source', 128)->index();
            $table->timestampTz('occurred_at')->index();
            $table->string('signature', 128)->nullable();
            $table->string('ip', 64)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->json('raw_json')->nullable();
            $table->timestampTz('received_at')->useCurrent();
            $table->index(['offer_id','source','occurred_at'], 'idx_clicks_offer_source_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clicks');
    }
};
