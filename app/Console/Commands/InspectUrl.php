<?php

namespace App\Console\Commands;

use App\Services\Google\GoogleIndexNowService;
use Illuminate\Console\Command;

class InspectUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:inspect {url} {--site-url=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inspeciona o status de indexação de uma URL no Google Search Console';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = 'https://afiliado.primeescolhas.com.br/produto/caixa-de-som-bluetooth-jbl-go-4-preta-prova-dagua';
        $siteUrl = 'sc-domain:primeescolhas.com.br';

        $this->info("🔍 Inspecionando URL: {$url}");
        $this->info("Site: {$siteUrl}");
        $this->newLine();

        try {
            $result = GoogleIndexNowService::inspectUrl($url, $siteUrl);

            // Status de Indexação
            if ($indexStatus = $result['indexStatus']) {
                $this->info("📊 Status de Indexação:");
                $this->line("  Veredicto: " . ($indexStatus->getVerdict() ?? 'N/A'));
                $this->line("  Estado de Cobertura: " . ($indexStatus->getCoverageState() ?? 'N/A'));
                $this->line("  Estado do Robots.txt: " . ($indexStatus->getRobotsTxtState() ?? 'N/A'));
                $this->line("  Estado de Indexação: " . ($indexStatus->getIndexingState() ?? 'N/A'));

                if ($lastCrawl = $indexStatus->getLastCrawlTime()) {
                    $this->line("  Último Crawl: " . $lastCrawl);
                }

                $this->line("  Estado do Fetch: " . ($indexStatus->getPageFetchState() ?? 'N/A'));

                if ($canonical = $indexStatus->getGoogleCanonical()) {
                    $this->line("  Canonical Google: " . $canonical);
                }

                if ($crawledAs = $indexStatus->getCrawledAs()) {
                    $this->line("  Crawled Como: " . $crawledAs);
                }

                $this->newLine();
            }

            // AMP Result
            if ($ampResult = $result['ampResult']) {
                $verdict = $ampResult->getVerdict() ?? 'N/A';
                $icon = $verdict === 'PASS' ? '✅' : '❌';
                $this->line("{$icon} AMP: {$verdict}");

                if ($ampUrl = $ampResult->getAmpUrl()) {
                    $this->line("  AMP URL: {$ampUrl}");
                }
                $this->newLine();
            }

            // Rich Results
            if ($richResults = $result['richResults']) {
                $verdict = $richResults->getVerdict() ?? 'N/A';
                $icon = $verdict === 'PASS' ? '✅' : '❌';
                $this->line("{$icon} Rich Results: {$verdict}");

                if ($detectedItems = $richResults->getDetectedItems()) {
                    $this->line("  Itens Detectados:");
                    foreach ($detectedItems as $item) {
                        $type = $item->getRichResultType() ?? 'Unknown';
                        $this->line("    - {$type}");
                    }
                }
                $this->newLine();
            }

            $this->info("✅ Inspeção concluída!");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ Erro ao inspecionar URL:");
            $this->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}
