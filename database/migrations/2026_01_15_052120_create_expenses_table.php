<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            // KISNE kharch kiya? (User Relationship)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // KIS CHEEZ pe kharch kiya? (Category Relationship)
            $table->foreignId('category_id')->constrained()->onDelete('cascade');

            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
