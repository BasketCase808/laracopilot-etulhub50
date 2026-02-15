<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('command_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('command');
            $table->text('parameters')->nullable();
            $table->longText('result')->nullable();
            $table->enum('status', ['success', 'error', 'blocked'])->default('success');
            $table->text('error_message')->nullable();
            $table->timestamp('executed_at');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('command');
            $table->index('status');
            $table->index('executed_at');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('command_logs');
    }
};