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
        // Add client_name column - no reference to vendor_or_client
        if (!Schema::hasColumn('transactions', 'client_name')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('client_name')->nullable()->after('description');
            });
        }
        
        // Now handle the type column conversion
        if (DB::connection()->getDriverName() === 'mysql') {
            // Temporarily change to VARCHAR to allow data modification
            DB::statement("ALTER TABLE transactions MODIFY COLUMN type VARCHAR(50) NOT NULL");
            
            // Update existing income records to budget_addition
            DB::table('transactions')
                ->where('type', 'income')
                ->update(['type' => 'budget_addition']);
            
            // Change back to ENUM with new values
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
        
        // Drop client_name column if it exists
        if (Schema::hasColumn('transactions', 'client_name')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn('client_name');
            });
        }
    }
};