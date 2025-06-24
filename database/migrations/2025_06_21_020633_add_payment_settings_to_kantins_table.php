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
        Schema::table('kantins', function (Blueprint $table) {
            $table->json('payment_methods')->nullable()->after('is_open');
            $table->text('payment_instructions')->nullable()->after('payment_methods');
            $table->boolean('auto_confirm_payment')->default(false)->after('payment_instructions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kantins', function (Blueprint $table) {
            $table->dropColumn(['payment_methods', 'payment_instructions', 'auto_confirm_payment']);
        });
    }
};
