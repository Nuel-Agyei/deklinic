<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venue_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., De Klinic Restaurant & Pub
            $table->string('owner_name'); // e.g., Portia Boateng
            $table->string('city')->default('Accra');
            $table->string('country')->default('Ghana');
            $table->text('summary'); // Local food & pub experience
            $table->text('community_story'); // Supporter-funded meals for five followers
            $table->json('sample_details')->nullable(); // Clearly marked sample/unconfirmed items: menu, prices, delivery, groups/events
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venue_profiles');
    }
};