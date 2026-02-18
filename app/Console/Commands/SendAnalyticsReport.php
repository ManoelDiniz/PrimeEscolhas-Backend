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
    protected $description = 'Relatório horário do GA4 com crescimento e top métricas';

    public function handle()
    {
        $propertyId = env('GA4_PROPERTY_ID');

        Log::alert('propertyId: ' . $propertyId);

        $client = new BetaAnalyticsDataClient([
            'credentials' => storage_path('app/google/service-account.json'),
        ]);

        // 🔹 Função para pegar métricas por período
        $getMetrics = function ($startMinutesAgo, $endMinutesAgo) use ($client, $propertyId) {

            $request = new RunReportRequest();
            $request->setProperty('properties/' . $propertyId);

            $dateRange = new DateRange();
            $dateRange->setStartDate((new \DateTime())->modify("-{$startMinutesAgo} minutes")->format('Y-m-d'));
            $dateRange->setEndDate((new \DateTime())->modify("-{$endMinutesAgo} minutes")->format('Y-m-d'));

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
                return [0, 0, 0, 0];
            }

            $metrics = $rows[0]->getMetricValues();

            // Verificar se há métricas suficientes
            if (count($metrics) < 4) {
                return [0, 0, 0, 0];
            }

            return [
                (int) $metrics[0]->getValue(),
                (int) $metrics[1]->getValue(),
                (int) $metrics[2]->getValue(),
                (int) $metrics[3]->getValue(),
            ];
        };

        // 🔹 Hora atual (últimos 60 minutos)
        [$currentUsers, $currentSessions, $currentViews, $currentEvents] =
            $getMetrics(60, 0);

        // 🔹 Hora anterior (120 a 60 minutos atrás)
        [$previousUsers, $previousSessions, $previousViews, $previousEvents] =
            $getMetrics(120, 60);

        // 🔹 Cálculo percentual
        $calcGrowth = function ($current, $previous) {
            if ($previous == 0) {
                return $current > 0 ? 100 : 0;
            }
            return round((($current - $previous) / $previous) * 100, 2);
        };

        $usersGrowth = $calcGrowth($currentUsers, $previousUsers);
        $sessionsGrowth = $calcGrowth($currentSessions, $previousSessions);
        $viewsGrowth = $calcGrowth($currentViews, $previousViews);
        $eventsGrowth = $calcGrowth($currentEvents, $previousEvents);

        $arrow = function ($value) {
            if ($value > 0) return "📈 +{$value}%";
            if ($value < 0) return "📉 {$value}%";
            return "➖ 0%";
        };

        // 🌍 Top Países
        $countriesRequest = new RunReportRequest();
        $countriesRequest->setProperty('properties/' . $propertyId);

        $dateRange = new DateRange();
        $dateRange->setStartDate((new \DateTime())->modify('-60 minutes')->format('Y-m-d'));
        $dateRange->setEndDate((new \DateTime())->format('Y-m-d'));

        $countriesRequest->setDateRanges([$dateRange]);
        $countriesRequest->setDimensions([
            new Dimension(['name' => 'country'])
        ]);
        $countriesRequest->setMetrics([
            new Metric(['name' => 'activeUsers'])
        ]);
        $countriesRequest->setLimit(3);

        $countriesResponse = $client->runReport($countriesRequest);

        $countries = "";
        $countryRows = $countriesResponse->getRows();
        if (!empty($countryRows)) {
            foreach ($countryRows as $row) {
                $dimensionValues = $row->getDimensionValues();
                $metricValues = $row->getMetricValues();

                if (count($dimensionValues) > 0 && count($metricValues) > 0) {
                    $country = $dimensionValues[0]->getValue();
                    $users = $metricValues[0]->getValue();
                    $countries .= "🌎 {$country}: {$users} usuários\n";
                }
            }
        }

        if (empty($countries)) {
            $countries = "Nenhum dado disponível\n";
        }

        // 🏆 Top Páginas
        $pagesRequest = new RunReportRequest();
        $pagesRequest->setProperty('properties/' . $propertyId);

        $dateRange = new DateRange();
        $dateRange->setStartDate((new \DateTime())->modify('-60 minutes')->format('Y-m-d'));
        $dateRange->setEndDate((new \DateTime())->format('Y-m-d'));

        $pagesRequest->setDateRanges([$dateRange]);
        $pagesRequest->setDimensions([
            new Dimension(['name' => 'pagePath'])
        ]);
        $pagesRequest->setMetrics([
            new Metric(['name' => 'screenPageViews'])
        ]);
        $pagesRequest->setLimit(3);

        $pagesResponse = $client->runReport($pagesRequest);

        $pages = "";
        $pageRows = $pagesResponse->getRows();
        if (!empty($pageRows)) {
            foreach ($pageRows as $row) {
                $dimensionValues = $row->getDimensionValues();
                $metricValues = $row->getMetricValues();

                if (count($dimensionValues) > 0 && count($metricValues) > 0) {
                    $page = $dimensionValues[0]->getValue();
                    $viewsCount = $metricValues[0]->getValue();
                    $pages .= "📄 {$page}: {$viewsCount} views\n";
                }
            }
        }

        if (empty($pages)) {
            $pages = "Nenhum dado disponível\n";
        }

        // 📤 Mensagem final
        $message = "📊 **Relatório Hora a Hora - Prime Escolhas**

👥 Usuários: {$currentUsers} ({$arrow($usersGrowth)})
📈 Sessões: {$currentSessions} ({$arrow($sessionsGrowth)})
📄 Visualizações: {$currentViews} ({$arrow($viewsGrowth)})
🎯 Eventos: {$currentEvents} ({$arrow($eventsGrowth)})

🕒 Comparação: Última hora vs hora anterior

🌍 **Top Países**
{$countries}

🏆 **Top Páginas**
{$pages}
";

        Http::post(env('DISCORD_WEBHOOK_URL_ANALYTICS'), [
            'content' => $message
        ]);

        $this->info('Relatório horário enviado com sucesso!');
    }
}
