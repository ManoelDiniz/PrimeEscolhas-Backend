<?php

namespace App\Console\Commands;

use App\Jobs\IndexUrlJob;
use App\Models\BlogPosts;
use App\Models\Products;
use App\Services\Google\GoogleIndexNowService;
use Illuminate\Console\Command;

class IndexAllUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:all {--type= : Tipo de conteúdo (blog, products, all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexa todas as URLs de posts e produtos no Google';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type') ?? 'all';
        $baseUrl = 'https://afiliado.primeescolhas.com.br';

        $totalIndexed = 0;
        $totalErrors = 0;

        // Indexar posts do blog
        if ($type === 'blog' || $type === 'all') {
            $this->info('Indexando posts do blog...');
            IndexUrlJob::dispatch("https://afiliado.primeescolhas.com.br/sitemap.xml");
            $posts = BlogPosts::where('is_published', true)->get();

            $bar = $this->output->createProgressBar($posts->count());
            $bar->start();

            foreach ($posts as $post) {
                try {
                    $url = "{$baseUrl}/blog/{$post->slug}";
                    GoogleIndexNowService::indexUrl($url);
                    $totalIndexed++;
                    $bar->advance();

                    // Aguardar 1 segundo entre requisições para evitar rate limit
                    sleep(1);
                } catch (\Exception $e) {
                    $totalErrors++;
                    $this->error("\nErro ao indexar {$post->slug}: {$e->getMessage()}");
                }
            }

            $bar->finish();
            $this->newLine();
        }

        // Indexar produtos
        if ($type === 'products' || $type === 'all') {
            $this->info('Indexando produtos...');
            $products = Products::all();

            $bar = $this->output->createProgressBar($products->count());
            $bar->start();

            foreach ($products as $product) {
                try {
                    $url = "{$baseUrl}/produto/{$product->slug}";
                    GoogleIndexNowService::indexUrl($url);
                    $totalIndexed++;
                    $bar->advance();

                    // Aguardar 1 segundo entre requisições para evitar rate limit
                    sleep(1);
                } catch (\Exception $e) {
                    $totalErrors++;
                    $this->error("\nErro ao indexar {$product->slug}: {$e->getMessage()}");
                }
            }

            $bar->finish();
            $this->newLine();
        }

        $this->newLine();
        $this->info("✅ Indexação concluída!");
        $this->info("Total indexado: {$totalIndexed}");

        if ($totalErrors > 0) {
            $this->warn("Total de erros: {$totalErrors}");
        }

        return Command::SUCCESS;
    }
}
