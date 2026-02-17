<?php

namespace App\Console\Commands;

use Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\RunReportRequest;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\Dimension;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendTrafficSourceAnalyticsReport extends Command
{
    protected $signature = 'analytics:traffic-source-report';
    protected $description = 'Relatório das fontes de tráfego do GA4';

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
        $request->setMetrics([
            new Metric(['name' => 'sessions']),
            new Metric(['name' => 'activeUsers']),
        ]);
        $request->setDimensions([
            new Dimension(['name' => 'source']),
            new Dimension(['name' => 'medium']),
        ]);
        $request->setLimit(5); // Reduzindo o limite para garantir dados mais relevantes

        $response = $client->runReport($request);
        $rows = $response->getRows();

        if (empty($rows)) {
            $this->info('Nenhum dado encontrado para o relatório de fontes de tráfego.');
            return;
        }

        $message = "📈 **Relatório de Fontes de Tráfego - Prime Escolhas**\n\n";
        foreach ($rows as $row) {
            $source = $row->getDimensionValues()[0]->getValue();
            $medium = $row->getDimensionValues()[1]->getValue();
            $sessions = $row->getMetricValues()[0]->getValue();
            $activeUsers = $row->getMetricValues()[1]->getValue();
            $message .= "🌐 {$source} / {$medium}: {$sessions} sessões, {$activeUsers} usuários ativos\n";
        }

        Http::post(env('DISCORD_WEBHOOK_URL_ANALYTICS'), [
            'content' => $message
        ]);

        $this->info('Relatório de fontes de tráfego enviado com sucesso!');
    }
}
