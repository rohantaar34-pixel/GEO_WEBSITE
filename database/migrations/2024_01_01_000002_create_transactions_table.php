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
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['income', 'expense']);
            $table->string('category');
            $table->decimal('amount', 12, 2);
            $table->text('description')->nullable();
            $table->date('transaction_date');
            $table->string('vendor_or_client')->nullable();
            $table->string('invoice_ref')->nullable();
            $table->timestamps();
            
            $table->index(['project_id', 'type', 'category']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};