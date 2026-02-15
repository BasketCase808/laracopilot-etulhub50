<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\ApiClient;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $clients = ApiClient::all();
        
        if ($clients->isEmpty()) {
            return;
        }
        
        $sampleTransactions = [
            [
                'txid' => 'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6',
                'address' => 'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh',
                'amount' => 0.05000000,
                'type' => 'send',
                'status' => 'confirmed',
                'confirmations' => 12,
                'comment' => 'Payment for Order #1001'
            ],
            [
                'txid' => 'b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6a1',
                'address' => 'bc1qar0srrr7xfkvy5l643lydnw9re59gtzzwf5mdq',
                'amount' => 0.10000000,
                'type' => 'send',
                'status' => 'confirmed',
                'confirmations' => 25,
                'comment' => 'Payment for Order #1002'
            ],
            [
                'txid' => 'c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6a1b2',
                'address' => 'bc1qw508d6qejxtdg4y5r3zarvary0c5xw7kv8f3t4',
                'amount' => 0.02500000,
                'type' => 'send',
                'status' => 'pending',
                'confirmations' => 2,
                'comment' => 'Payment for Order #1003'
            ],
            [
                'txid' => 'd4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6a1b2c3',
                'address' => 'bc1qrp33g0q5c5txsp9arysrx4k6zdkfs4nce4xj0gdcccefvpysxf3qccfmv3',
                'amount' => 0.15000000,
                'type' => 'receive',
                'status' => 'confirmed',
                'confirmations' => 50,
                'comment' => 'Received payment from client'
            ],
            [
                'txid' => 'e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6a1b2c3d4',
                'address' => 'bc1q2zy6kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh',
                'amount' => 0.00750000,
                'type' => 'send',
                'status' => 'confirmed',
                'confirmations' => 8,
                'comment' => 'Payment for Order #1004'
            ]
        ];
        
        foreach ($sampleTransactions as $txData) {
            Transaction::create(array_merge($txData, [
                'api_client_id' => $clients->random()->id
            ]));
        }
        
        // Generate additional random transactions
        for ($i = 0; $i < 15; $i++) {
            Transaction::create([
                'api_client_id' => $clients->random()->id,
                'txid' => bin2hex(random_bytes(32)),
                'address' => 'bc1q' . bin2hex(random_bytes(20)),
                'amount' => rand(100000, 50000000) / 100000000,
                'type' => rand(0, 1) ? 'send' : 'receive',
                'status' => ['pending', 'confirmed', 'confirmed', 'confirmed'][rand(0, 3)],
                'confirmations' => rand(0, 100),
                'comment' => 'Transaction #' . (1005 + $i)
            ]);
        }
    }
}