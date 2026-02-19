<?php

namespace App\Filament\Resources\BlogPosts\Pages;

use App\Filament\Resources\BlogPosts\BlogPostsResource;
use App\Jobs\IndexUrlJob;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogPosts extends CreateRecord
{
    protected static string $resource = BlogPostsResource::class;

    public function afterCreate(): void
    {
        $blogPost = $this->record;
        $url = 'https://afiliado.primeescolhas.com.br/blog/' . $blogPost->slug;

        IndexUrlJob::dispatch($url)
            ->onQueue('indexing')
            ->delay(now()->addSeconds(10));
    }
}
