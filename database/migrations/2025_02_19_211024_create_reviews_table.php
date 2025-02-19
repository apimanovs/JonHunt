<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('ReviewID');
            // Полиморфные поля:
            $table->unsignedBigInteger('reviewable_id');
            $table->string('reviewable_type');
            
            // Идентификаторы пользователей:
            $table->foreignId('UserID')->constrained('users');
            $table->foreignId('ReviewedUserID')->constrained('users');
            
            $table->unsignedTinyInteger('Rating');
            $table->text('ReviewText')->nullable();
            $table->timestamps();
            
            // (Опционально) можно добавить индекс для быстрого поиска по reviewable:
            $table->index(['reviewable_id', 'reviewable_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
