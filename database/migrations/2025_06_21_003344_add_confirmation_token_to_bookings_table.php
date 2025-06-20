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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('confirmation_token', 64)->nullable()->unique()->after('is_confirmed');
            $table->timestamp('confirmation_token_expires_at')->nullable()->after('confirmation_token');
            $table->index(['confirmation_token', 'confirmation_token_expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['confirmation_token', 'confirmation_token_expires_at']);
            $table->dropColumn(['confirmation_token', 'confirmation_token_expires_at']);
        });
    }
};
