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


        IndexUrlJob::dispatch("https://afiliado.primeescolhas.com.br/sitemap.xml");
    }
}
