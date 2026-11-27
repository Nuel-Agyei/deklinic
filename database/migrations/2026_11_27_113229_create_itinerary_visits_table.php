<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itinerary_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_profile_id')->constrained()->cascadeOnDelete();
            $table->string('token')->unique(); // For shareable itinerary & referral links
            $table->string('referral_code')->unique(); // For diaspora/curator referral tracking

            // Stage 01: Discover abroad
            $table->string('discovery_source'); // e.g., diaspora_referral, ai_travel_answer, search, curated_itinerary
            $table->string('discovery_label'); // Human-readable source shown in demo
            $table->string('origin_region'); // e.g., UK, North America, Europe, Africa

            // Stage 02: Decide before flying
            $table->string('guest_name');
            $table->string('guest_contact')->nullable(); // Optional email/handle
            $table->string('target_window')->nullable(); // e.g., December 2026
            $table->unsignedTinyInteger('party_size')->default(1);
            $table->text('trip_note')->nullable(); // Pre-trip interest / group / meal note

            // Stage 03: Visit in Ghana (status flow: saved -> confirmed)
            $table->string('status')->default('saved'); // saved | confirmed
            $table->timestamp('confirmed_at')->nullable();
            $table->string('confirmed_by')->nullable(); // Staff member name/role in demo

            // Stage 04: Refer and return
            $table->text('referral_note')->nullable();
            $table->string('shared_with_channel')->nullable(); // e.g., Diaspora Group, Travel Curator, Host
            $table->timestamp('shared_at')->nullable();

            // Optional LLM-generated framing (populated later)
            $table->text('ai_itinerary_brief')->nullable();
            $table->text('ai_referral_draft')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itinerary_visits');
    }
};