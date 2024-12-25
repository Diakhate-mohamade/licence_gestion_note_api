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
        Schema::create('coefficients_ec', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');

            $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade');

            $table->float('coefficient', 3, 2); // Coefficient spécifique à cette classe
            $table->timestamps();

            // Assurer l'unicité du couple classe_id et matiere_id
            $table->unique(['classe_id', 'matiere_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coefficients_ec');
    }
};
