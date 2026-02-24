<?php

namespace App\Console\Commands;

use App\Models\ProductsShopee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetProductShopee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-product-shopee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $appId = env('APP_ID_SHOPEE');
        $appSecret = env('APP_SECRET_SHOPEE');
        $query = <<<'GRAPHQL'
query ($page: Int, $limit: Int) {
    productOfferV2(page: $page, limit: $limit) {
        nodes {
            productName
            itemId
            commissionRate
            commission
            price
            sales
            imageUrl
            shopName
            productLink
            offerLink
            periodStartTime
            periodEndTime
            priceMin
            priceMax
            productCatIds
            ratingStar
            priceDiscountRate
            shopId
            shopType
            sellerCommissionRate
            shopeeCommissionRate
        }
        pageInfo {
            page
            limit
            hasNextPage
            scrollId
        }
    }
}
GRAPHQL;

        if (!$appId || !$appSecret) {
            $this->error('APP_ID_SHOPEE ou APP_SECRET_SHOPEE não configurados.');
            return Command::FAILURE;
        }

        $page = 1;
        $limit = 20;
        $hasNextPage = true;
        $saved = 0;

        while ($hasNextPage) {
            $timestamp = time();
            $payload = json_encode([
                'query' => $query,
                'variables' => [
                    'page' => $page,
                    'limit' => $limit,
                ],
            ], JSON_UNESCAPED_SLASHES);

            $authorization = $this->buildAuthorizationHeader($appId, $timestamp, $payload, $appSecret);
            $response = Http::withHeaders([
                'Authorization' => $authorization,
                'Content-Type' => 'application/json',
            ])->withBody($payload, 'application/json')
                ->post('https://open-api.affiliate.shopee.com.br/graphql');

            $data = $response->json();
            $nodes = data_get($data, 'data.productOfferV2.nodes', []);
            $pageInfo = data_get($data, 'data.productOfferV2.pageInfo', []);

            if (!is_array($nodes) || empty($nodes)) {
                $this->error('Nenhum produto retornado pela Shopee.');
                $this->line(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                return Command::FAILURE;
            }

            foreach ($nodes as $node) {
                if (!isset($node['itemId'])) {
                    continue;
                }

                ProductsShopee::updateOrCreate(
                    ['item_id' => (int) $node['itemId']],
                    [
                        'product_name' => $node['productName'] ?? '',
                        'commission_rate' => $node['commissionRate'] ?? 0,
                        'commission' => $node['commission'] ?? 0,
                        'price' => $node['price'] ?? 0,
                        'sales' => $node['sales'] ?? 0,
                        'image_url' => $node['imageUrl'] ?? '',
                        'shop_name' => $node['shopName'] ?? '',
                        'product_link' => $node['productLink'] ?? '',
                        'offer_link' => $node['offerLink'] ?? '',
                        'period_start_time' => $node['periodStartTime'] ?? 0,
                        'period_end_time' => $node['periodEndTime'] ?? 0,
                        'price_min' => $node['priceMin'] ?? 0,
                        'price_max' => $node['priceMax'] ?? 0,
                        'product_cat_ids' => $node['productCatIds'] ?? [],
                        'rating_star' => $node['ratingStar'] ?? 0,
                        'price_discount_rate' => $node['priceDiscountRate'] ?? 0,
                        'shop_id' => $node['shopId'] ?? 0,
                        'shop_type' => $node['shopType'] ?? [],
                        'seller_commission_rate' => $node['sellerCommissionRate'] ?? 0,
                        'shopee_commission_rate' => $node['shopeeCommissionRate'] ?? 0,
                    ]
                );

                $saved++;
            }

            $hasNextPage = (bool) data_get($pageInfo, 'hasNextPage', false);
            $page = (int) data_get($pageInfo, 'page', $page) + 1;
        }

        $this->info("Produtos salvos/atualizados: {$saved}");
        return Command::SUCCESS;
    }

    private function buildAuthorizationHeader(string $appId, int $timestamp, string $payload, string $appSecret): string
    {
        $signatureBase = $appId . $timestamp . $payload . $appSecret;
        $signature = hash('sha256', $signatureBase);

        return sprintf(
            'SHA256 Credential=%s, Timestamp=%s, Signature=%s',
            $appId,
            $timestamp,
            $signature
        );
    }
}
