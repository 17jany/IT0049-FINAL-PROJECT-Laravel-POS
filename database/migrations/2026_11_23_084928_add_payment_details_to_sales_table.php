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
        Schema::table('sales', function (Blueprint $table) {

            // Cash or credit payment
            $table->string('payment_method')
                ->default('cash');

            // Amount paid by the customer
            $table->decimal('cash_received', 12, 2)
                ->nullable();

            // Change given to the customer
            $table->decimal('change_amount', 12, 2)
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'cash_received',
                'change_amount',
            ]);
        });
    }
};
