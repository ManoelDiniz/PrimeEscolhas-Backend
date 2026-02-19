<?php

namespace App\Console\Commands;

use App\Jobs\IndexUrlJob;
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
            $this->info("Enviando URL do produto: {$product->title} para indexação...");
            $url = 'https://afiliado.primeescolhas.com.br/produto/' . $product->slug;
            IndexUrlJob::dispatch($url)
                ->onQueue('indexing')
                ->delay(now()->addSeconds(5)); // Pequeno delay para evitar sobrecarga

            $this->info('URL enviada para indexação com sucesso 🚀');
        }
    }
}
