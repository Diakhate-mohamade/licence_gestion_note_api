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
        Schema::create('u_e_s', function (Blueprint $table) {
            $table->id();
            $table->integer('credit');
            $table->foreignId('id_note_mcc')->constrained('note_mccs')->onDelete('cascade');
            $table->foreignId('id_note_examen')->constrained('note_examens')->onDelete('cascade');
            $table->float('moyenneCoef', 3, 2);// Coefficient calculée
            $table->decimal('moyenneUE', 5, 2)->nullable(); // Moyenne calculée
            $table->string('appreciation')->nullable(); // Appréciation
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('u_e_s');
    }
};
