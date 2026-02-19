<?php

namespace App\Console\Commands;

use App\Models\Products;
use App\Services\Google\GoogleIndexNowService;
use Illuminate\Console\Command;


class IndexTestUrl extends Command
{
    protected $signature = 'index:test';
    protected $description = 'Testa indexação de URL específica';

    public function handle()
    {


        $products = Products::get();

        foreach ($products as $product) {
            $this->info("Enviando URL do produto: {$product->name} para indexação...");
            $url = 'https://afiliado.primeescolhas.com.br/produto/' . $product->slug;
            $indexer = new GoogleIndexNowService();
            $indexer->indexUrl($url);

            $this->info('URL enviada para indexação com sucesso 🚀');
        }
    }
}
