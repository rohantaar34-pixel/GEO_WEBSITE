<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Add FK for dropdown category
            $table->foreignId('expense_category_id')->nullable()->after('type');
            $table->string('expense_name')->after('expense_category_id');

            // Add FK constraint
            $table->foreign('expense_category_id')
                ->references('id')
                ->on('expense_categories')
                ->nullOnDelete();

            // Remove vendor storage
            if (Schema::hasColumn('transactions', 'vendor_or_client')) {
                $table->dropColumn('vendor_or_client');
            }

            // We keep existing `category` for now; controller/UI will switch to FK.
            // Later you can clean it up once you confirm no old data relies on it.
        });

        // Make expense_name required (Laravel only supports altering in two steps safely)
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('expense_name')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Drop FK
            if (Schema::hasColumn('transactions', 'expense_category_id')) {
                $table->dropForeign(['expense_category_id']);
                $table->dropColumn('expense_category_id');
            }

            if (Schema::hasColumn('transactions', 'expense_name')) {
                $table->dropColumn('expense_name');
            }

            // Restore vendor column
            if (!Schema::hasColumn('transactions', 'vendor_or_client')) {
                $table->string('vendor_or_client')->nullable()->after('transaction_date');
            }
        });
    }
};

