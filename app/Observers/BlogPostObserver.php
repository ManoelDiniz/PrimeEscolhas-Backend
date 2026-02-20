<?php

namespace App\Observers;

use App\Models\BlogPosts;
use App\Services\Google\GoogleIndexNowService;
use Illuminate\Support\Facades\Log;

class BlogPostObserver
{
    /**
     * Handle the BlogPosts "created" event.
     */
    public function created(BlogPosts $blogPost): void
    {
        $this->indexUrl($blogPost);
    }

    /**
     * Handle the BlogPosts "updated" event.
     */
    public function updated(BlogPosts $blogPost): void
    {
        $this->indexUrl($blogPost);
    }

    /**
     * Handle the BlogPosts "deleted" event.
     */
    public function deleted(BlogPosts $blogPost): void
    {
        try {
            $url = config('app.url') . '/blog/' . $blogPost->slug;
            GoogleIndexNowService::indexUrl($url, 'URL_DELETED');
            Log::info("URL deletada indexada: {$url}");
        } catch (\Exception $e) {
            Log::error("Erro ao indexar URL deletada: {$e->getMessage()}");
        }
    }

    /**
     * Indexa a URL do post no Google
     */
    private function indexUrl(BlogPosts $blogPost): void
    {
        if (!$blogPost->is_published) {
            return;
        }

        try {
            $url = config('app.url') . '/blog/' . $blogPost->slug;
            GoogleIndexNowService::indexUrl($url);
            Log::info("URL indexada automaticamente: {$url}");
        } catch (\Exception $e) {
            Log::error("Erro ao indexar URL: {$e->getMessage()}");
        }
    }
}
