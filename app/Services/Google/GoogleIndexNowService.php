<?php

namespace App\Services\Google;

use Google\Client;
use Google\Service\Indexing;
use Google\Service\Indexing\UrlNotification;

class GoogleIndexNowService
{
    public static function indexUrl(string $url)
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google/service-account.json'));
        $client->addScope(Indexing::INDEXING);

        $service = new Indexing($client);

        $urlNotification = new UrlNotification();
        $urlNotification->setUrl($url);
        $urlNotification->setType('URL_UPDATED');

        try {
            $response = $service->urlNotifications->publish($urlNotification);
            return $response;
        } catch (\Exception $e) {
            throw new \Exception('Erro ao notificar URL: ' . $e->getMessage());
        }
    }
}
