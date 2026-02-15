<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('api_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('website');
            $table->text('description')->nullable();
            $table->string('api_key', 64)->unique();
            $table->string('api_secret', 128);
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index('api_key');
            $table->index('active');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('api_clients');
    }
};