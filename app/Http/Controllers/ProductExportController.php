<?php

namespace App\Http\Controllers;

use App\Services\ProductExportService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductExportController extends Controller
{
    private ProductExportService $exportService;

    public function __construct(ProductExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    /**
     * Export products as Google Shopping XML feed
     */
    public function googleShopping(Request $request): Response
    {
        $tenant = app('tenant');
        
        $xml = $this->exportService->generateGoogleShoppingFeed($tenant);

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="google-shopping-feed.xml"');
    }

    /**
     * Export products as Meta (Facebook/Instagram) XML feed
     */
    public function meta(Request $request): Response
    {
        $tenant = app('tenant');
        
        $xml = $this->exportService->generateMetaFeed($tenant);

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="meta-feed.xml"');
    }
}
