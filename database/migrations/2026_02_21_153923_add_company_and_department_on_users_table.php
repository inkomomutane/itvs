<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', static function (Blueprint $table) {
            $table->string('company')->nullable()->after('sap_number');
            $table->string('department')->nullable()->after('company');
            $table->boolean('active')->default(true)->after('department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users',static  function (Blueprint $table) {
            $table->dropColumn(['company', 'department','active']);
        });
    }
};
