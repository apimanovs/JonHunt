<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('order_id')->after('ReceiverID')->constrained('orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('order_id');
        });
    }

};
