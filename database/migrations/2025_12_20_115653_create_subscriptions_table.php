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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('payment_gateway_id')->nullable()->constrained('payment_gateways')->onDelete('set null');
            $table->string('plan_name');
            $table->integer('amount'); // Em centavos
            $table->string('currency', 3)->default('BRL');
            $table->enum('status', ['active', 'cancelled', 'expired', 'pending'])->default('pending');
            $table->enum('billing_cycle', ['monthly', 'yearly'])->default('monthly');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('next_billing_date')->nullable();
            $table->string('abacatepay_subscription_id')->nullable()->unique();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            // Índices para performance
            $table->index(['tenant_id', 'status'], 'subscriptions_tenant_status_idx');
            $table->index(['next_billing_date'], 'subscriptions_next_billing_date_idx');
            $table->index(['abacatepay_subscription_id'], 'subscriptions_abacatepay_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
