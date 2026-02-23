<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('category_recipe', function (Blueprint $table) {
            $table->foreignId('recipe_id')->after('id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->after('recipe_id')->constrained()->cascadeOnDelete();

            $table->unique(['recipe_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::table('category_recipe', function (Blueprint $table) {
            $table->dropUnique(['recipe_id', 'category_id']);
            $table->dropForeign(['recipe_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn(['recipe_id', 'category_id']);
        });
    }
};
