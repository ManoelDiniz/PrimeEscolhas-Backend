<?php

namespace App\Services\Google;

use Google\Client;
use Google\Service\Indexing;
use Google\Service\Indexing\UrlNotification;
use Google\Service\SearchConsole;

class GoogleIndexNowService
{
    /**
     * Indexa uma URL no Google
     */
    public static function indexUrl(string $url, string $type = 'URL_UPDATED')
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google/service-account.json'));
        $client->addScope(Indexing::INDEXING);

        $service = new Indexing($client);
        $urlNotification = new UrlNotification();

        $urlNotification->setUrl($url);
        $urlNotification->setType($type);

        try {
            $response = $service->urlNotifications->publish($urlNotification, []);
            return $response;
        } catch (\Exception $e) {
            throw new \Exception('Erro ao notificar URL: ' . $e->getMessage());
        }
    }

    /**
     * Inspeciona o status de indexação de uma URL no Google Search Console
     */
    public static function inspectUrl(string $url, string $siteUrl)
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google/service-account.json'));
        $client->addScope(SearchConsole::WEBMASTERS_READONLY);

        $service = new SearchConsole($client);

        try {
            $inspectRequest = new \Google\Service\SearchConsole\InspectUrlIndexRequest();
            $inspectRequest->setInspectionUrl($url);
            $inspectRequest->setSiteUrl($siteUrl);

            $response = $service->urlInspection_index->inspect($inspectRequest);
            $inspectionResult = $response->getInspectionResult();

            return [
                'url' => $url,
                'indexStatus' => $inspectionResult->getIndexStatusResult(),
                'richResults' => $inspectionResult->getRichResultsResult(),
                'ampResult' => $inspectionResult->getAmpResult(),
            ];
        } catch (\Exception $e) {
            throw new \Exception('Erro ao inspecionar URL: ' . $e->getMessage());
        }
    }

    /**
     * Obtém metadados de uma URL indexada
     */
    public static function getMetadata(string $url)
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google/service-account.json'));
        $client->addScope(Indexing::INDEXING);

        $service = new Indexing($client);

        try {
            $response = $service->urlNotifications->getMetadata(['url' => $url]);
            return $response;
        } catch (\Exception $e) {
            throw new \Exception('Erro ao obter metadados: ' . $e->getMessage());
        }
    }
}
