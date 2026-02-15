<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('api_clients', function (Blueprint $table) {
            $table->decimal('max_transaction_amount', 16, 8)->default(0.1)->after('allowed_ips');
            $table->decimal('daily_transaction_limit', 16, 8)->nullable()->after('max_transaction_amount');
            $table->boolean('can_send_transactions')->default(true)->after('daily_transaction_limit');
            $table->boolean('can_generate_addresses')->default(true)->after('can_send_transactions');
            $table->boolean('can_view_balance')->default(true)->after('can_generate_addresses');
            $table->boolean('can_list_transactions')->default(true)->after('can_view_balance');
            $table->boolean('can_validate_addresses')->default(true)->after('can_list_transactions');
            $table->text('blocked_commands')->nullable()->after('can_validate_addresses');
        });
    }
    
    public function down()
    {
        Schema::table('api_clients', function (Blueprint $table) {
            $table->dropColumn([
                'max_transaction_amount',
                'daily_transaction_limit',
                'can_send_transactions',
                'can_generate_addresses',
                'can_view_balance',
                'can_list_transactions',
                'can_validate_addresses',
                'blocked_commands'
            ]);
        });
    }
};