
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {

            // Suki or Credit customer
            $table->string('customer_type')
                ->default('suki');

            // Maximum credit allowed
            $table->decimal('credit_limit', 12, 2)
                ->default(0);

            // Outstanding credit balance
            $table->decimal('balance', 12, 2)
                ->default(0);

            // Archive instead of permanent deletion
            $table->boolean('is_archived')
                ->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'customer_type',
                'credit_limit',
                'balance',
                'is_archived',
            ]);
        });
    }
};
