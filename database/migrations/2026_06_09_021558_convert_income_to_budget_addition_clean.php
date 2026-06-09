<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add client_name column if it doesn't exist
        if (!Schema::hasColumn('transactions', 'client_name')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('client_name')->nullable()->after('description');
            });
        }
        
        // Convert the type column
        if (DB::connection()->getDriverName() === 'mysql') {
            // Change column to VARCHAR first
            DB::statement("ALTER TABLE transactions MODIFY COLUMN type VARCHAR(50) NOT NULL");
            
            // Update values
            DB::table('transactions')
                ->where('type', 'income')
                ->update(['type' => 'budget_addition']);
            
            // Change back to ENUM
            DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('expense', 'budget_addition') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE transactions MODIFY COLUMN type VARCHAR(50) NOT NULL");
            
            DB::table('transactions')
                ->where('type', 'budget_addition')
                ->update(['type' => 'income']);
            
            DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('income', 'expense') NOT NULL");
        }
        
        if (Schema::hasColumn('transactions', 'client_name')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn('client_name');
            });
        }
    }
};