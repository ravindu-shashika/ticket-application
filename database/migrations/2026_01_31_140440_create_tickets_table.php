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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('reference_number')->unique();
            $table->enum('status', ['new','in_progress', 'closed'])->default('new');
            $table->enum('priority', ['low', 'medium', 'high'])->default('low');
            $table->foreignId('customer_id')->constrained('customers');
            $table->timestamps();

            $table->index('reference_number');
            $table->index('customer_id');
            $table->index('status');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
