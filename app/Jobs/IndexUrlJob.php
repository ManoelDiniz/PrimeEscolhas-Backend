<?php

namespace App\Jobs;

use App\Services\Google\GoogleIndexNowService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IndexUrlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function handle(): void
    {
        $indexer = new GoogleIndexNowService();
        $indexer->indexUrl($this->url);
    }
}
