<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrdersStatusEnum extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE orders MODIFY status ENUM('pending', 'in_progress', 'submitted', 'completed', 'cancelled') NOT NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE orders MODIFY status ENUM('in_progress', 'submitted', 'completed', 'cancelled') NOT NULL");
    }
}
