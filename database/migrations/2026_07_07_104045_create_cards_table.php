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
     Schema::create('cards', function (Blueprint $table) {

    $table->id();

    $table->foreignId('user_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->string('card_holder');

    $table->string('card_number');

    $table->decimal('balance',15,2);

    $table->string('expired_date');

    $table->enum('theme',[
        'gradient',
        'light'
    ])->default('gradient');

    $table->boolean('is_active')
        ->default(true);

    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
