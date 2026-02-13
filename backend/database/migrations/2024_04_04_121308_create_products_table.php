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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("description")->nullable();
            $table->string("sumary")->nullable();
            $table->text("categories")->nullable();
            $table->string("slug")->unique();
            $table->text("imgs");
            $table->decimal("price");
            $table->integer("quantity");
            $table->unsignedBigInteger("blog_id");
            $table->unsignedBigInteger("type_id");
            $table->decimal("purchase_price");
            $table->decimal("sale_price");
            $table->integer("taxes_porcent");
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('type_id')->references('id')->on('products_types')->onDelete('cascade')->onUpdate('cascade');
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
