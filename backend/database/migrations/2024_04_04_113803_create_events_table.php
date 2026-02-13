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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blog_id');
            $table->string('title');
            $table->string('subtitle');
            $table->string('slug')->unique();
            $table->string('banner_img')->nullable();
            $table->longText('content');
            $table->datetime('start_dtime');
            $table->datetime('end_dtime');
            $table->string('ubication')->nullable();
            $table->string('section')->nullable();
            $table->integer('capacity')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('events', function (Blueprint $table) {
            $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
