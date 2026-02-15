<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_client_id')->constrained()->onDelete('cascade');
            $table->string('txid', 64)->unique();
            $table->string('address');
            $table->decimal('amount', 16, 8);
            $table->enum('type', ['send', 'receive'])->default('send');
            $table->enum('status', ['pending', 'confirmed', 'failed'])->default('pending');
            $table->integer('confirmations')->default(0);
            $table->text('comment')->nullable();
            $table->timestamps();
            
            $table->index('txid');
            $table->index('api_client_id');
            $table->index('status');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};