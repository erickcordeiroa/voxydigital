<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Support\Facades\Storage;

class ProductExportService
{
    /**
     * Generate Google Shopping XML feed
     */
    public function generateGoogleShoppingFeed(Tenant $tenant): string
    {
        $products = Product::where('tenant_id', $tenant->id)
            ->where('status', true)
            ->with(['images', 'category'])
            ->get();

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss xmlns:g="http://base.google.com/ns/1.0" version="2.0"></rss>');
        $channel = $xml->addChild('channel');
        $channel->addChild('title', htmlspecialchars($tenant->name));
        $channel->addChild('link', url('/'));
        $channel->addChild('description', htmlspecialchars($tenant->name . ' - Catálogo de Produtos'));

        foreach ($products as $product) {
            $item = $channel->addChild('item');
            
            // ID do produto
            $item->addChild('g:id', htmlspecialchars((string) $product->id));
            
            // Título
            $item->addChild('g:title', htmlspecialchars($product->name));
            
            // Descrição
            $description = strip_tags($product->description ?? $product->name);
            $item->addChild('g:description', htmlspecialchars(substr($description, 0, 5000)));
            
            // Link do produto
            $item->addChild('g:link', route('product.show', ['slug' => $product->slug]));
            
            // Imagem
            $firstImage = $product->images->first();
            if ($firstImage) {
                $imageUrl = Storage::url($firstImage->uri);
                $item->addChild('g:image_link', url($imageUrl));
            }
            
            // Preço (formato: BRL 99.99)
            $price = ($product->sale && $product->sale > 0) ? $product->sale : $product->price;
            $item->addChild('g:price', number_format($price / 100, 2, '.', '') . ' BRL');
            
            // Disponibilidade
            $item->addChild('g:availability', 'in stock');
            
            // Condição
            $item->addChild('g:condition', 'new');
            
            // Categoria
            if ($product->category) {
                $item->addChild('g:product_type', htmlspecialchars($product->category->name));
            }
            
            // Brand (usando nome do tenant)
            $item->addChild('g:brand', htmlspecialchars($tenant->name));
        }

        return $xml->asXML();
    }

    /**
     * Generate Meta (Facebook/Instagram) XML feed
     */
    public function generateMetaFeed(Tenant $tenant): string
    {
        $products = Product::where('tenant_id', $tenant->id)
            ->where('status', true)
            ->with(['images', 'category'])
            ->get();

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss xmlns:g="http://base.google.com/ns/1.0" version="2.0"></rss>');
        $channel = $xml->addChild('channel');
        $channel->addChild('title', htmlspecialchars($tenant->name));
        $channel->addChild('link', url('/'));
        $channel->addChild('description', htmlspecialchars($tenant->name . ' - Catálogo de Produtos'));

        foreach ($products as $product) {
            $item = $channel->addChild('item');
            
            // ID do produto
            $item->addChild('g:id', htmlspecialchars((string) $product->id));
            
            // Título
            $item->addChild('g:title', htmlspecialchars($product->name));
            
            // Descrição
            $description = strip_tags($product->description ?? $product->name);
            $item->addChild('g:description', htmlspecialchars(substr($description, 0, 5000)));
            
            // Link do produto
            $item->addChild('g:link', route('product.show', ['slug' => $product->slug]));
            
            // Imagem
            $firstImage = $product->images->first();
            if ($firstImage) {
                $imageUrl = Storage::url($firstImage->uri);
                $item->addChild('g:image_link', url($imageUrl));
            }
            
            // Preço (formato: BRL 99.99)
            $price = ($product->sale && $product->sale > 0) ? $product->sale : $product->price;
            $item->addChild('g:price', number_format($price / 100, 2, '.', '') . ' BRL');
            
            // Disponibilidade
            $item->addChild('g:availability', 'in stock');
            
            // Condição
            $item->addChild('g:condition', 'new');
            
            // Categoria
            if ($product->category) {
                $item->addChild('g:product_type', htmlspecialchars($product->category->name));
            }
            
            // Brand (usando nome do tenant)
            $item->addChild('g:brand', htmlspecialchars($tenant->name));
        }

        return $xml->asXML();
    }
}

