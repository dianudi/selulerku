<?php

use App\Models\ProductCategory;
use App\Models\User;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreignIdFor(ProductCategory::class)->constrained()->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->string('name');
            $table->text('description');
            $table->string('sku')->unique();
            $table->integer('quantity');
            $table->float('price');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
