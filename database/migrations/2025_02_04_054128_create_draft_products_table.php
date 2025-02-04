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
        Schema::create('draft_products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('product_code')->required()->unique();
            $table->string('manufacturer_name')->required();
            $table->decimal('mrp', 10, 2)->required(); 
            $table->text('combination_string')->required();
            $table->boolean('is_banned')->default(false);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_discontinued')->default(false);
            $table->boolean('is_assured')->default(false);
            $table->boolean('is_refrigerated')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_published')->default(false);
            $table->foreignId('category_id')->constrained('categories'); 
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draft_products');
    }
};