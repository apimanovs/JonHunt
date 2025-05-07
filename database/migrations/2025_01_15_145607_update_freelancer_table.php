<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('freelancers', function (Blueprint $table) {

            $table->integer('experience_from')->nullable();
            $table->integer('experience_to')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('freelancers', function (Blueprint $table) {
            if (Schema::hasColumn('freelancers', 'experience_from')) {
                $table->dropColumn('experience_from');
            }
            if (Schema::hasColumn('freelancers', 'experience_to')) {
                $table->dropColumn('experience_to');
            }


        });
    }
};
