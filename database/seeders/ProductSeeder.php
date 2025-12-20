<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $tenantId = 3;

        foreach ($categories as $category) {
            $products = $this->getProductsForCategory($category->name, $category->id, $tenantId);
            
            foreach ($products as $index => $productData) {
                $baseSlug = Str::slug($productData['name']);
                $uniqueSlug = $baseSlug . '-' . time() . '-' . $category->id . '-' . $index;
                $productData['slug'] = $uniqueSlug;
                
                Product::firstOrCreate([
                    'slug' => $uniqueSlug,
                ], $productData);
            }
        }
    }

    private function getProductsForCategory(string $categoryName, int $categoryId, int $tenantId): array
    {
        $products = [];

        switch ($categoryName) {
            case 'Camisetas':
                $products = [
                    ['name' => 'Camiseta Básica Algodão', 'description' => 'Camiseta básica 100% algodão, confortável e versátil.', 'price' => 4990, 'sale' => 3990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Camiseta Estampada', 'description' => 'Camiseta com estampa exclusiva, vários modelos disponíveis.', 'price' => 6990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Camiseta Polo', 'description' => 'Camiseta polo clássica, ideal para ocasiões casuais e formais.', 'price' => 8990, 'sale' => 7490, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Camiseta Manga Longa', 'description' => 'Camiseta manga longa para dias mais frescos.', 'price' => 7990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Camiseta Regata', 'description' => 'Regata confortável para o verão, diversos modelos.', 'price' => 3990, 'sale' => 2990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Camiseta V-Neck', 'description' => 'Camiseta gola V, estilo casual e moderno.', 'price' => 5990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Camiseta Oversized', 'description' => 'Camiseta oversized, tendência atual do streetwear.', 'price' => 6990, 'sale' => 5990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Camiseta Dry Fit', 'description' => 'Camiseta dry fit, ideal para atividades físicas.', 'price' => 8990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Camiseta Básica Branca', 'description' => 'Camiseta básica branca, essencial no guarda-roupa.', 'price' => 3990, 'sale' => 2990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Camiseta Básica Preta', 'description' => 'Camiseta básica preta, versátil e atemporal.', 'price' => 3990, 'sale' => 2990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Camiseta Listrada', 'description' => 'Camiseta listrada, estilo marítimo clássico.', 'price' => 5990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Camiseta Tie-Dye', 'description' => 'Camiseta tie-dye, cores vibrantes e únicas.', 'price' => 7990, 'sale' => 6990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                ];
                break;

            case 'Calças':
                $products = [
                    ['name' => 'Calça Jeans Skinny', 'description' => 'Calça jeans skinny, modelo ajustado e moderno.', 'price' => 14990, 'sale' => 12990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Calça Jeans Reta', 'description' => 'Calça jeans reta, corte clássico e confortável.', 'price' => 14990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Calça Jeans Wide Leg', 'description' => 'Calça jeans wide leg, tendência atual.', 'price' => 16990, 'sale' => 14990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Calça Social', 'description' => 'Calça social, ideal para ambiente de trabalho.', 'price' => 19990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Calça Moletom', 'description' => 'Calça moletom, conforto para o dia a dia.', 'price' => 8990, 'sale' => 7990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Calça Cargo', 'description' => 'Calça cargo com bolsos, estilo utilitário.', 'price' => 12990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Calça Legging', 'description' => 'Calça legging, conforto e flexibilidade.', 'price' => 6990, 'sale' => 5990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Calça Palazzo', 'description' => 'Calça palazzo, modelo amplo e elegante.', 'price' => 11990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Calça Jogger', 'description' => 'Calça jogger, estilo esportivo e casual.', 'price' => 9990, 'sale' => 8990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Calça Chino', 'description' => 'Calça chino, versátil entre casual e formal.', 'price' => 13990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Calça Jeans Destroyed', 'description' => 'Calça jeans destroyed, estilo despojado.', 'price' => 17990, 'sale' => 15990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Calça Alfaiataria', 'description' => 'Calça alfaiataria, elegante e sofisticada.', 'price' => 18990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                ];
                break;

            case 'Vestidos':
                $products = [
                    ['name' => 'Vestido Midi', 'description' => 'Vestido midi, elegante e versátil.', 'price' => 12990, 'sale' => 10990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Vestido Longo', 'description' => 'Vestido longo, ideal para ocasiões especiais.', 'price' => 19990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Vestido Curto', 'description' => 'Vestido curto, casual e descontraído.', 'price' => 8990, 'sale' => 7990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Vestido Floral', 'description' => 'Vestido com estampa floral, feminino e delicado.', 'price' => 11990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Vestido Social', 'description' => 'Vestido social, adequado para ambiente profissional.', 'price' => 14990, 'sale' => 12990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Vestido Tubinho', 'description' => 'Vestido tubinho, modelo clássico e atemporal.', 'price' => 9990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Vestido Evasê', 'description' => 'Vestido evasê, modelo amplo e confortável.', 'price' => 13990, 'sale' => 11990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Vestido Manga Longa', 'description' => 'Vestido manga longa, elegante e sofisticado.', 'price' => 15990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Vestido Estampado', 'description' => 'Vestido estampado, diversos modelos disponíveis.', 'price' => 11990, 'sale' => 9990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Vestido Liso', 'description' => 'Vestido liso, versátil e clássico.', 'price' => 10990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Vestido Festa', 'description' => 'Vestido para festa, elegante e sofisticado.', 'price' => 22990, 'sale' => 19990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Vestido Casual', 'description' => 'Vestido casual, confortável para o dia a dia.', 'price' => 8990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                ];
                break;

            case 'Jaquetas':
                $products = [
                    ['name' => 'Jaqueta Jeans', 'description' => 'Jaqueta jeans clássica, atemporal e versátil.', 'price' => 14990, 'sale' => 12990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Jaqueta Couro', 'description' => 'Jaqueta de couro, estilo rock e sofisticado.', 'price' => 39990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Jaqueta Moletom', 'description' => 'Jaqueta moletom, conforto e estilo casual.', 'price' => 11990, 'sale' => 9990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Jaqueta Bomber', 'description' => 'Jaqueta bomber, estilo esportivo e moderno.', 'price' => 17990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Casaco Inverno', 'description' => 'Casaco para inverno, quente e confortável.', 'price' => 24990, 'sale' => 21990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Jaqueta Corta-Vento', 'description' => 'Jaqueta corta-vento, ideal para atividades ao ar livre.', 'price' => 13990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Blazer', 'description' => 'Blazer, elegante para ocasiões formais e casuais.', 'price' => 19990, 'sale' => 17990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Jaqueta Parka', 'description' => 'Jaqueta parka, estilo urbano e funcional.', 'price' => 21990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Jaqueta Motoqueiro', 'description' => 'Jaqueta estilo motoqueiro, robusta e estilosa.', 'price' => 27990, 'sale' => 24990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Casaco Soft Shell', 'description' => 'Casaco soft shell, leve e resistente.', 'price' => 18990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Jaqueta Denim', 'description' => 'Jaqueta denim, clássica e versátil.', 'price' => 15990, 'sale' => 13990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Casaco Térmico', 'description' => 'Casaco térmico, proteção contra o frio.', 'price' => 22990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                ];
                break;

            case 'Tênis':
                $products = [
                    ['name' => 'Tênis Esportivo', 'description' => 'Tênis esportivo, ideal para corrida e caminhada.', 'price' => 29990, 'sale' => 24990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Tênis Casual', 'description' => 'Tênis casual, confortável para o dia a dia.', 'price' => 19990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Tênis Skate', 'description' => 'Tênis para skate, resistente e estiloso.', 'price' => 24990, 'sale' => 21990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Tênis Running', 'description' => 'Tênis para corrida, tecnologia de amortecimento.', 'price' => 34990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Tênis Cano Alto', 'description' => 'Tênis cano alto, estilo urbano e moderno.', 'price' => 27990, 'sale' => 24990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Tênis Minimalista', 'description' => 'Tênis minimalista, design limpo e elegante.', 'price' => 17990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Tênis Basquete', 'description' => 'Tênis para basquete, suporte e amortecimento.', 'price' => 39990, 'sale' => 34990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Tênis Futebol Society', 'description' => 'Tênis para futebol society, tração e conforto.', 'price' => 22990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Tênis Lifestyle', 'description' => 'Tênis lifestyle, estilo e conforto combinados.', 'price' => 21990, 'sale' => 19990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Tênis Slip-On', 'description' => 'Tênis slip-on, praticidade e estilo.', 'price' => 16990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Tênis Retro', 'description' => 'Tênis estilo retrô, nostalgia e moda.', 'price' => 25990, 'sale' => 22990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Tênis Premium', 'description' => 'Tênis premium, qualidade superior e design exclusivo.', 'price' => 44990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                ];
                break;

            case 'Botas':
                $products = [
                    ['name' => 'Bota Ankle Boot', 'description' => 'Bota ankle boot, elegante e versátil.', 'price' => 24990, 'sale' => 21990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Bota Cano Médio', 'description' => 'Bota cano médio, estilo casual e confortável.', 'price' => 27990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Bota Cano Longo', 'description' => 'Bota cano longo, proteção e estilo.', 'price' => 32990, 'sale' => 29990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Bota Couro', 'description' => 'Bota de couro legítimo, durabilidade e elegância.', 'price' => 39990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Bota Social', 'description' => 'Bota social, adequada para ambiente profissional.', 'price' => 29990, 'sale' => 26990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Bota Chelsea', 'description' => 'Bota chelsea, clássica e atemporal.', 'price' => 26990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Bota Militar', 'description' => 'Bota estilo militar, robusta e estilosa.', 'price' => 31990, 'sale' => 28990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Bota Inverno', 'description' => 'Bota para inverno, quente e impermeável.', 'price' => 34990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Bota Cowboy', 'description' => 'Bota estilo cowboy, autêntica e única.', 'price' => 37990, 'sale' => 34990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Bota Casual', 'description' => 'Bota casual, confortável para o dia a dia.', 'price' => 22990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Bota Cano Curto', 'description' => 'Bota cano curto, versátil e moderna.', 'price' => 23990, 'sale' => 21990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Bota Premium', 'description' => 'Bota premium, qualidade superior e design exclusivo.', 'price' => 44990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                ];
                break;

            case 'Sandálias':
                $products = [
                    ['name' => 'Sandália Rasteira', 'description' => 'Sandália rasteira, confortável e versátil.', 'price' => 6990, 'sale' => 5990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Sandália Anabela', 'description' => 'Sandália anabela, elegante e confortável.', 'price' => 8990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Sandália Plataforma', 'description' => 'Sandália plataforma, altura e estilo.', 'price' => 11990, 'sale' => 9990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Sandália Gladiadora', 'description' => 'Sandália gladiadora, estilo único e moderno.', 'price' => 9990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Chinelo Slide', 'description' => 'Chinelo slide, praticidade e conforto.', 'price' => 4990, 'sale' => 3990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Sandália Social', 'description' => 'Sandália social, elegante para ocasiões especiais.', 'price' => 12990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId], 
                    ['name' => 'Sandália Tiras', 'description' => 'Sandália de tiras, feminina e delicada.', 'price' => 7990, 'sale' => 6990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Sandália Casual', 'description' => 'Sandália casual, confortável para o dia a dia.', 'price' => 6990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Sandália Salto', 'description' => 'Sandália com salto, elegante e sofisticada.', 'price' => 14990, 'sale' => 12990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Chinelo Havaianas', 'description' => 'Chinelo estilo havaianas, clássico brasileiro.', 'price' => 3990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Sandália Esportiva', 'description' => 'Sandália esportiva, ideal para atividades ao ar livre.', 'price' => 8990, 'sale' => 7990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Sandália Premium', 'description' => 'Sandália premium, qualidade superior e design exclusivo.', 'price' => 17990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                ];
                break;

            case 'Acessórios':
                $products = [
                    ['name' => 'Bolsa Tote', 'description' => 'Bolsa tote, espaçosa e versátil.', 'price' => 8990, 'sale' => 7990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Bolsa Crossbody', 'description' => 'Bolsa crossbody, prática e moderna.', 'price' => 7990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Mochila', 'description' => 'Mochila, funcional e estilosa.', 'price' => 12990, 'sale' => 10990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Cinto Couro', 'description' => 'Cinto de couro legítimo, elegante e durável.', 'price' => 5990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Carteira', 'description' => 'Carteira, diversos modelos disponíveis.', 'price' => 4990, 'sale' => 3990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Relógio', 'description' => 'Relógio, elegante e funcional.', 'price' => 19990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Óculos de Sol', 'description' => 'Óculos de sol, proteção e estilo.', 'price' => 8990, 'sale' => 7990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Gorro', 'description' => 'Gorro, proteção contra o frio.', 'price' => 3990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Luvas', 'description' => 'Luvas, conforto e proteção.', 'price' => 4990, 'sale' => 3990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Lenço', 'description' => 'Lenço, acessório versátil e elegante.', 'price' => 2990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Pulseira', 'description' => 'Pulseira, diversos modelos e estilos.', 'price' => 1990, 'sale' => 1490, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Colar', 'description' => 'Colar, elegante e sofisticado.', 'price' => 6990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                ];
                break;

            case 'Infantil':
                $products = [
                    ['name' => 'Camiseta Infantil', 'description' => 'Camiseta infantil, confortável e colorida.', 'price' => 3990, 'sale' => 2990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Calça Infantil', 'description' => 'Calça infantil, resistente e confortável.', 'price' => 6990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Vestido Infantil', 'description' => 'Vestido infantil, delicado e colorido.', 'price' => 7990, 'sale' => 6990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Tênis Infantil', 'description' => 'Tênis infantil, confortável e resistente.', 'price' => 14990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Sandália Infantil', 'description' => 'Sandália infantil, prática e confortável.', 'price' => 4990, 'sale' => 3990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Jaqueta Infantil', 'description' => 'Jaqueta infantil, proteção e estilo.', 'price' => 9990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Shorts Infantil', 'description' => 'Shorts infantil, confortável para brincar.', 'price' => 4990, 'sale' => 3990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Saia Infantil', 'description' => 'Saia infantil, delicada e colorida.', 'price' => 5990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Mochila Infantil', 'description' => 'Mochila infantil, divertida e funcional.', 'price' => 7990, 'sale' => 6990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Boné Infantil', 'description' => 'Boné infantil, proteção solar e estilo.', 'price' => 3990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId], 
                    ['name' => 'Conjunto Infantil', 'description' => 'Conjunto infantil, camiseta e shorts combinados.', 'price' => 8990, 'sale' => 7990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Pijama Infantil', 'description' => 'Pijama infantil, confortável para dormir.', 'price' => 6990, 'sale' => null, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                ];
                break;

            case 'Promoções':
                $products = [
                    ['name' => 'Kit 3 Camisetas', 'description' => 'Kit com 3 camisetas básicas, promoção especial.', 'price' => 9990, 'sale' => 7990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Calça Jeans Promoção', 'description' => 'Calça jeans em promoção, qualidade garantida.', 'price' => 9990, 'sale' => 7990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Tênis Liquidação', 'description' => 'Tênis em liquidação, últimos pares disponíveis.', 'price' => 14990, 'sale' => 9990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Vestido Oferta', 'description' => 'Vestido em oferta especial, diversos modelos.', 'price' => 6990, 'sale' => 4990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Jaqueta Desconto', 'description' => 'Jaqueta com desconto especial, estoque limitado.', 'price' => 9990, 'sale' => 7990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Sandália Promoção', 'description' => 'Sandália em promoção, conforto e economia.', 'price' => 3990, 'sale' => 2990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Acessórios Kit', 'description' => 'Kit de acessórios, cinto e carteira combinados.', 'price' => 6990, 'sale' => 4990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Roupas Infantis Promoção', 'description' => 'Roupas infantis em promoção, variedade de modelos.', 'price' => 4990, 'sale' => 3990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Liquidação Geral', 'description' => 'Liquidação geral, produtos selecionados com desconto.', 'price' => 9990, 'sale' => 6990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Oferta Relâmpago', 'description' => 'Oferta relâmpago, desconto por tempo limitado.', 'price' => 7990, 'sale' => 5990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Promoção Black Friday', 'description' => 'Promoção estilo Black Friday, descontos imperdíveis.', 'price' => 14990, 'sale' => 9990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                    ['name' => 'Mega Promoção', 'description' => 'Mega promoção, produtos com até 50% de desconto.', 'price' => 19990, 'sale' => 9990, 'status' => true, 'category_id' => $categoryId, 'tenant_id' => $tenantId],
                ];
                break;
        }

        return $products;
    }
}
