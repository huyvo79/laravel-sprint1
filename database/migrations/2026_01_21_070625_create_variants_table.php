<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->double('price', 15, 2)->default(0);
            $table->integer('position')->default(0);
            $table->double('compare_at_price', 15, 2)->nullable();
            $table->string('option_1');
            $table->string('option_2')->nullable();
            $table->string('option_3')->nullable();
            $table->integer('inventory_quantity')->default(0);
            $table->string('image_url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
