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
            // Índice composto para buscar pedidos por tenant, status de pagamento e data
            if (!$this->hasIndex('orders', 'orders_tenant_payment_status_created_idx')) {
                $table->index(['tenant_id', 'payment_status', 'created_at'], 'orders_tenant_payment_status_created_idx');
            }
            
            // Índice único para payment_id (usado em webhooks) - apenas se payment_id não for nulo
            if (!$this->hasIndex('orders', 'orders_payment_id_unique')) {
                try {
                    $table->unique('payment_id', 'orders_payment_id_unique');
                } catch (\Exception $e) {
                    // Se falhar (pode ter valores nulos duplicados), criar índice normal
                    $table->index('payment_id', 'orders_payment_id_idx');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if ($this->hasIndex('orders', 'orders_tenant_payment_status_created_idx')) {
                $table->dropIndex('orders_tenant_payment_status_created_idx');
            }
            if ($this->hasIndex('orders', 'orders_payment_id_unique')) {
                $table->dropUnique('orders_payment_id_unique');
            }
            if ($this->hasIndex('orders', 'orders_payment_id_idx')) {
                $table->dropIndex('orders_payment_id_idx');
            }
        });
    }

    /**
     * Verifica se um índice já existe
     */
    private function hasIndex(string $table, string $indexName): bool
    {
        try {
            $connection = Schema::getConnection();
            $databaseName = $connection->getDatabaseName();
            
            $result = $connection->select(
                "SELECT COUNT(*) as count FROM information_schema.statistics 
                 WHERE table_schema = ? AND table_name = ? AND index_name = ?",
                [$databaseName, $table, $indexName]
            );
            
            return isset($result[0]) && $result[0]->count > 0;
        } catch (\Exception $e) {
            // Se não conseguir verificar, assume que não existe
            return false;
        }
    }
};
