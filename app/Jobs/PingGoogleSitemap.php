<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class PingGoogleSitemap implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $sitemap = urlencode('https://afiliado.primeescolhas.com.br/sitemap.xml');

        Http::get("https://www.google.com/ping?sitemap={$sitemap}");
    }
}
