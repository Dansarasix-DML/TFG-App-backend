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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->longText('content');
            $table->unsignedBigInteger('post_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable(); // If the comment is a reply to another comment
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade')->onUpdate('cascade');
        });

        // DB::statement("
        //     CREATE DEFINER = CURRENT_USER TRIGGER game_verse.comments_BEFORE_INSERT BEFORE INSERT ON comments FOR EACH ROW
        //     BEGIN
        //         IF (NEW.post_id IS NULL AND NEW.parent_id IS NULL) OR (NEW.post_id IS NOT NULL AND NEW.parent_id IS NOT NULL) THEN
        //             SIGNAL SQLSTATE '45000'
        //             SET MESSAGE_TEXT = 'Debe proporcionar un valor para exactamente una de las columnas post_id o parent_id';
        //         END IF;
        //     END
        // ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
