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
        Schema::table('products', function (Blueprint $table) {
            // Índices para melhorar performance das consultas
            $table->index(['tenant_id', 'status', 'created_at'], 'products_tenant_status_created_idx');
            $table->index(['category_id', 'status'], 'products_category_status_idx');
            $table->index(['slug'], 'products_slug_idx');
            $table->index(['name'], 'products_name_idx');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index(['tenant_id', 'status'], 'categories_tenant_status_idx');
            $table->index(['slug'], 'categories_slug_idx');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['tenant_id', 'status', 'created_at'], 'orders_tenant_status_created_idx');
            $table->index(['customer_phone'], 'orders_customer_phone_idx');
            $table->index(['created_at'], 'orders_created_at_idx');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->index(['order_id'], 'order_items_order_id_idx');
            $table->index(['product_id'], 'order_items_product_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_tenant_status_created_idx');
            $table->dropIndex('products_category_status_idx');
            $table->dropIndex('products_slug_idx');
            $table->dropIndex('products_name_idx');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('categories_tenant_status_idx');
            $table->dropIndex('categories_slug_idx');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_tenant_status_created_idx');
            $table->dropIndex('orders_customer_phone_idx');
            $table->dropIndex('orders_created_at_idx');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('order_items_order_id_idx');
            $table->dropIndex('order_items_product_id_idx');
        });
    }
};
