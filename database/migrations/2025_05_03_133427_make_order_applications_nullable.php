<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('job_application_id')->nullable()->change();
            $table->unsignedBigInteger('project_application_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('job_application_id')->nullable(false)->change();
            $table->unsignedBigInteger('project_application_id')->nullable()->change();
        });
    }
};
