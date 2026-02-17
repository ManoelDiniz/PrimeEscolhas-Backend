<?php

namespace App\Console\Commands;

use Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\RunReportRequest;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\Dimension;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendDeviceAnalyticsReport extends Command
{
    protected $signature = 'analytics:device-report';
    protected $description = 'Relatório de dispositivos do GA4 com distribuição de acessos';

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
            new Dimension(['name' => 'deviceCategory']),
        ]);
        $request->setMetrics([
            new Metric(['name' => 'activeUsers']),
        ]);

        $response = $client->runReport($request);
        $rows = $response->getRows();

        if (empty($rows)) {
            $this->info('Nenhum dado encontrado para o relatório de dispositivos.');
            return;
        }

        $message = "📊 **Relatório de Dispositivos - Prime Escolhas**\n\n";
        foreach ($rows as $row) {
            $device = $row->getDimensionValues()[0]->getValue();
            $users = $row->getMetricValues()[0]->getValue();
            $message .= "📱 {$device}: {$users} usuários\n";
        }

        Http::post(env('DISCORD_WEBHOOK_URL_ANALYTICS'), [
            'content' => $message
        ]);

        $this->info('Relatório de dispositivos enviado com sucesso!');
    }
}
