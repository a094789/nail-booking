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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->datetime('booking_time');
            $table->string('customer_name');
            $table->string('customer_line_name');
            $table->string('customer_line_id');
            $table->string('customer_phone');
            $table->boolean('need_removal')->default(false);
            $table->enum('style_type', ['single_color', 'design']);
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'cancelled', 'completed'])->default('pending');
            $table->text('cancellation_reason')->nullable();
            $table->decimal('amount', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};