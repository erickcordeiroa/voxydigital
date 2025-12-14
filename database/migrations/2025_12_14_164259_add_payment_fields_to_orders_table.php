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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('status'); // 'pix' ou 'credit_card'
            $table->string('payment_id')->nullable()->after('payment_method'); // ID do pagamento no Mercado Pago
            $table->string('payment_status')->nullable()->after('payment_id'); // approved, pending, rejected, etc
            $table->text('qr_code')->nullable()->after('payment_status'); // QR Code do PIX
            $table->text('qr_code_base64')->nullable()->after('qr_code'); // QR Code em base64
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_id', 'payment_status', 'qr_code', 'qr_code_base64']);
        });
    }
};
