<?php

use App\Enum\MealPeriod;
use App\Enum\MealStatus;
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
        Schema::create('meals', static function (Blueprint $table) {
            $table->ulid('id')->primary();
            // Foreign keys
            $table->foreignUlid('recipe_id')->constrained('recipes')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUlid('worker_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('meal_date');
            $table->string('period')->default(MealPeriod::Breakfast)->comment('breakfast, lunch, dinner');

            // Timing
            $table->dateTime('reserved_at')->nullable();
            $table->dateTime('served_at')->nullable();

            $table->string('status')->default(MealStatus::Reserved)->comment('meal reservation status'); // reserved, aten

            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['recipe_id','worker_id','meal_date','period']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
