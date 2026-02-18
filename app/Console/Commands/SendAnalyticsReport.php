<?php

namespace App\Console\Commands;

use Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\RunReportRequest;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\Dimension;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendAnalyticsReport extends Command
{
    protected $signature = 'analytics:report';
    protected $description = 'Relatório horário GA4 melhorado';

    public function handle()
    {
        try {

            $propertyId = env('GA4_PROPERTY_ID');

            $client = new BetaAnalyticsDataClient([
                'credentials' => storage_path('app/google/service-account.json'),
            ]);

            $getMetrics = function ($startDate, $endDate) use ($client, $propertyId) {

                $request = new RunReportRequest();
                $request->setProperty("properties/{$propertyId}");

                $request->setDateRanges([
                    new DateRange([
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                    ])
                ]);

                $request->setMetrics([
                    new Metric(['name' => 'activeUsers']),
                    new Metric(['name' => 'sessions']),
                    new Metric(['name' => 'screenPageViews']),
                    new Metric(['name' => 'eventCount']),
                ]);

                $response = $client->runReport($request);
                $rows = $response->getRows();

                if ($rows === null || $rows->count() === 0) {
                    return [0, 0, 0, 0];
                }

                $row = $rows->offsetGet(0);
                $metrics = $row->getMetricValues();

                return [
                    (int) ($metrics[0]->getValue() ?? 0),
                    (int) ($metrics[1]->getValue() ?? 0),
                    (int) ($metrics[2]->getValue() ?? 0),
                    (int) ($metrics[3]->getValue() ?? 0),
                ];
            };

            $today = now()->format('Y-m-d');
            $yesterday = now()->subDay()->format('Y-m-d');

            [$users, $sessions, $views, $events] =
                $getMetrics($today, $today);

            [$usersYesterday] =
                $getMetrics($yesterday, $yesterday);

            $growth = function ($current, $previous) {
                if ($previous == 0) return $current > 0 ? 100 : 0;
                return round((($current - $previous) / $previous) * 100, 2);
            };

            $formatGrowth = function ($value) {
                if ($value > 0) return "📈 +{$value}%";
                if ($value < 0) return "📉 {$value}%";
                return "➖ 0%";
            };

            $usersGrowth = $growth($users, $usersYesterday);

            $status = "⚖️ Estável";
            if ($usersGrowth > 20) $status = "🔥 TRÁFEGO EM ALTA";
            if ($usersGrowth < -20) $status = "🚨 QUEDA FORTE";

            // Top Países
            $countriesRequest = new RunReportRequest();
            $countriesRequest->setProperty("properties/{$propertyId}");
            $countriesRequest->setDateRanges([
                new DateRange([
                    'start_date' => $today,
                    'end_date' => $today,
                ])
            ]);
            $countriesRequest->setDimensions([
                new Dimension(['name' => 'country'])
            ]);
            $countriesRequest->setMetrics([
                new Metric(['name' => 'activeUsers'])
            ]);
            $countriesRequest->setLimit(3);

            $countriesResponse = $client->runReport($countriesRequest);
            $countries = "";

            foreach ($countriesResponse->getRows() ?? [] as $row) {
                $country = $row->getDimensionValues()[0]->getValue() ?? '-';
                $count = $row->getMetricValues()[0]->getValue() ?? 0;
                $countries .= "{$country} - {$count}\n";
            }

            if (!$countries) $countries = "Sem dados\n";

            // Top Páginas
            $pagesRequest = new RunReportRequest();
            $pagesRequest->setProperty("properties/{$propertyId}");
            $pagesRequest->setDateRanges([
                new DateRange([
                    'start_date' => $today,
                    'end_date' => $today,
                ])
            ]);
            $pagesRequest->setDimensions([
                new Dimension(['name' => 'pagePath'])
            ]);
            $pagesRequest->setMetrics([
                new Metric(['name' => 'screenPageViews'])
            ]);
            $pagesRequest->setLimit(3);

            $pagesResponse = $client->runReport($pagesRequest);
            $pages = "";

            foreach ($pagesResponse->getRows() ?? [] as $row) {
                $page = $row->getDimensionValues()[0]->getValue() ?? '-';
                $count = $row->getMetricValues()[0]->getValue() ?? 0;
                $pages .= "{$page} - {$count}\n";
            }

            if (!$pages) $pages = "Sem dados\n";

            $message = "📊 **RELATÓRIO ANALYTICS - PRIME ESCOLHAS**

⏱ Comparação: Hoje vs Ontem

👥 Usuários: {$users} ({$formatGrowth($usersGrowth)})
📈 Sessões: {$sessions}
📄 Views: {$views}
🎯 Eventos: {$events}

$status

🌎 **Top Países**
{$countries}

🏆 **Top Páginas**
{$pages}

🕒 Atualizado: " . now()->format('H:i');

            Http::timeout(10)->post(env('DISCORD_WEBHOOK_URL_ANALYTICS'), [
                'content' => $message
            ]);

            $this->info('Relatório enviado com sucesso!');
        } catch (\Throwable $e) {
            Log::error('Erro analytics: ' . $e->getMessage());
        }
    }
}
