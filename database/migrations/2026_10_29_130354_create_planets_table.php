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
        Schema::create('planets', function (Blueprint $table) {
            $table->id();

            // Index de la planète dans le jeu ("index" est un mot réservé en MySQL)
            $table->unsignedSmallInteger('game_index')->unique();

            // Données statiques (endpoint /planets)
            $table->string('name', 100)->nullable();
            $table->string('sector', 50)->nullable();
            $table->string('biome_slug', 50)->nullable();
            $table->text('biome_description')->nullable();
            $table->json('environmentals')->nullable();

            // Dernier état de la guerre (endpoints /war/status et /war/info)
            // owner : 1 Humains, 2 Terminides, 3 Automates, 4 Illuminés
            $table->unsignedTinyInteger('owner')->nullable();
            $table->unsignedInteger('health')->nullable();
            $table->unsignedInteger('max_health')->nullable();
            $table->float('regen_per_second')->nullable();
            $table->unsignedInteger('players')->nullable();
            $table->double('position_x')->nullable();
            $table->double('position_y')->nullable();
            $table->timestamp('war_synced_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planets');
    }
};
