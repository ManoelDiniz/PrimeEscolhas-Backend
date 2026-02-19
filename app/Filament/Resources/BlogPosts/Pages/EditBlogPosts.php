<?php

namespace App\Filament\Resources\BlogPosts\Pages;

use App\Filament\Resources\BlogPosts\BlogPostsResource;
use App\Jobs\IndexUrlJob;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBlogPosts extends EditRecord
{
    protected static string $resource = BlogPostsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function afterSave(): void
    {
        $blogPost = $this->record;
        $url = 'https://afiliado.primeescolhas.com.br/blog/' . $blogPost->slug;

        IndexUrlJob::dispatch($url)
            ->onQueue('indexing')
            ->delay(now()->addSeconds(10));

        $this->notify('success', 'URL enviada para fila de indexação 🚀');
    }
}
