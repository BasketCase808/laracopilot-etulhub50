<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add is_admin to users table
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('email');
        });
        
        // Add whitelist fields to api_clients table
        Schema::table('api_clients', function (Blueprint $table) {
            $table->text('allowed_domains')->nullable()->after('active');
            $table->text('allowed_ips')->nullable()->after('allowed_domains');
        });
    }
    
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
        
        Schema::table('api_clients', function (Blueprint $table) {
            $table->dropColumn(['allowed_domains', 'allowed_ips']);
        });
    }
};