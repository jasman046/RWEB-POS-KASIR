<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('transactions', function (Blueprint $table) {

    $table->id();

    $table->foreignId('card_id')
          ->constrained()
          ->cascadeOnDelete();

    $table->string('transaction_code')->unique();

    $table->string('description');

    $table->string('type');

    $table->decimal('amount',15,2);

    $table->dateTime('transaction_date');

    $table->enum('category',[
        'Income',
        'Expense'
    ]);

    $table->string('receipt_path')->nullable();

    $table->enum('status',[
        'Completed',
        'Pending',
        'Cancelled'
    ])->default('Completed');

    $table->timestamps();

});
}

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};