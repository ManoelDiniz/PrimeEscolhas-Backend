<?php

namespace App\Console\Commands;

use Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\RunReportRequest;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Metric;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendDailyAnalyticsReport extends Command
{
    protected $signature = 'analytics:daily-report';
    protected $description = 'Relatório diário do GA4 com métricas acumuladas';

    public function handle()
    {
        $propertyId = env('GA4_PROPERTY_ID');

        $client = new BetaAnalyticsDataClient([
            'credentials' => storage_path('app/google/service-account.json'),
        ]);

        $request = new RunReportRequest();
        $request->setProperty('properties/' . $propertyId);

        $dateRange = new DateRange();
        $dateRange->setStartDate('yesterday');
        $dateRange->setEndDate('yesterday');

        $request->setDateRanges([$dateRange]);
        $request->setMetrics([
            new Metric(['name' => 'activeUsers']),
            new Metric(['name' => 'sessions']),
            new Metric(['name' => 'screenPageViews']),
            new Metric(['name' => 'eventCount']),
        ]);

        $response = $client->runReport($request);
        $rows = $response->getRows();

        if (empty($rows)) {
            $this->info('Nenhum dado encontrado para o relatório diário.');
            return;
        }

        $metrics = $rows[0]->getMetricValues();

        $message = "📊 **Relatório Diário - Prime Escolhas**\n\n" .
            "👥 Usuários: " . $metrics[0]->getValue() . "\n" .
            "📈 Sessões: " . $metrics[1]->getValue() . "\n" .
            "📄 Visualizações: " . $metrics[2]->getValue() . "\n" .
            "🎯 Eventos: " . $metrics[3]->getValue() . "\n";

        Http::post(env('DISCORD_WEBHOOK_URL_ANALYTICS'), [
            'content' => $message
        ]);

        $this->info('Relatório diário enviado com sucesso!');
    }
}
