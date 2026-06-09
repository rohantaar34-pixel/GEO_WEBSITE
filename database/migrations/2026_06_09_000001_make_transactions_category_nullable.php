<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Make legacy column safe for new inserts while app migrates to FK-based categories.
            if (Schema::hasColumn('transactions', 'category')) {
                $table->string('category')->nullable()->change();
            }
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'category')) {
                $table->string('category')->nullable(false)->change();
            }
        });
    }
};

