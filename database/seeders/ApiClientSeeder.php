<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApiClient;
use Illuminate\Support\Str;

class ApiClientSeeder extends Seeder
{
    public function run()
    {
        $clients = [
            [
                'name' => 'Bitcoin Shop Demo',
                'email' => 'admin@bitcoinshop.com',
                'website' => 'https://bitcoinshop.com',
                'description' => 'E-commerce store accepting Bitcoin payments',
                'api_key' => 'btc_demo_key_' . Str::random(20),
                'api_secret' => Str::random(64),
                'active' => true
            ],
            [
                'name' => 'Crypto Marketplace',
                'email' => 'api@cryptomarket.io',
                'website' => 'https://cryptomarket.io',
                'description' => 'Multi-vendor crypto marketplace',
                'api_key' => 'btc_market_key_' . Str::random(20),
                'api_secret' => Str::random(64),
                'active' => true
            ],
            [
                'name' => 'Digital Goods Store',
                'email' => 'support@digitalgoods.net',
                'website' => 'https://digitalgoods.net',
                'description' => 'Digital products with Bitcoin payment',
                'api_key' => 'btc_digital_key_' . Str::random(20),
                'api_secret' => Str::random(64),
                'active' => true
            ]
        ];
        
        foreach ($clients as $client) {
            ApiClient::create($client);
        }
    }
}