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
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('provider'); // mercadopago, abacatepay, etc
            $table->string('name')->nullable(); // Nome amigável do gateway
            $table->boolean('is_active')->default(true);
            $table->json('credentials'); // Credenciais específicas do provider (access_token, public_key, etc)
            $table->timestamps();
            
            // Índices para performance
            $table->index(['tenant_id', 'provider', 'is_active'], 'payment_gateways_tenant_provider_active_idx');
            $table->index(['tenant_id', 'is_active'], 'payment_gateways_tenant_active_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
