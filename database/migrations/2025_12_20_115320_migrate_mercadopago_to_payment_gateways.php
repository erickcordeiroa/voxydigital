<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrar configurações do Mercado Pago da tabela tenants para payment_gateways
        $tenants = DB::table('tenants')
            ->whereNotNull('mp_access_token')
            ->orWhereNotNull('mp_public_key')
            ->get();

        foreach ($tenants as $tenant) {
            // Só cria gateway se tiver pelo menos access_token
            if ($tenant->mp_access_token) {
                $credentials = [
                    'access_token' => $tenant->mp_access_token,
                ];

                if ($tenant->mp_public_key) {
                    $credentials['public_key'] = $tenant->mp_public_key;
                }

                DB::table('payment_gateways')->insert([
                    'tenant_id' => $tenant->id,
                    'provider' => 'mercadopago',
                    'name' => 'Mercado Pago',
                    'is_active' => true,
                    'credentials' => json_encode($credentials),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover gateways do Mercado Pago criados por esta migration
        DB::table('payment_gateways')
            ->where('provider', 'mercadopago')
            ->delete();
    }
};
