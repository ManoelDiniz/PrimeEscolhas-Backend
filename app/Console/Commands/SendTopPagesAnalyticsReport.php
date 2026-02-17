<?php

namespace App\Console\Commands;

use Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\RunReportRequest;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\Dimension;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendTopPagesAnalyticsReport extends Command
{
    protected $signature = 'analytics:top-pages-report';
    protected $description = 'Relatório das páginas mais acessadas do GA4';

    public function handle()
    {
        $propertyId = env('GA4_PROPERTY_ID');

        $client = new BetaAnalyticsDataClient([
            'credentials' => storage_path('app/google/service-account.json'),
        ]);

        $request = new RunReportRequest();
        $request->setProperty('properties/' . $propertyId);

        $dateRange = new DateRange();
        $dateRange->setStartDate('7daysAgo');
        $dateRange->setEndDate('yesterday');

        $request->setDateRanges([$dateRange]);
        $request->setDimensions([
            new Dimension(['name' => 'pagePath']),
        ]);
        $request->setMetrics([
            new Metric(['name' => 'screenPageViews']),
        ]);
        $request->setLimit(10);

        $response = $client->runReport($request);
        $rows = $response->getRows();

        if (empty($rows)) {
            $this->info('Nenhum dado encontrado para o relatório de páginas mais acessadas.');
            return;
        }

        $message = "📊 **Relatório de Páginas Mais Acessadas - Prime Escolhas**\n\n";
        foreach ($rows as $row) {
            $page = $row->getDimensionValues()[0]->getValue();
            $views = $row->getMetricValues()[0]->getValue();
            $message .= "📄 {$page}: {$views} visualizações\n";
        }

        Http::post(env('DISCORD_WEBHOOK_URL_ANALYTICS'), [
            'content' => $message
        ]);

        $this->info('Relatório de páginas mais acessadas enviado com sucesso!');
    }
}
