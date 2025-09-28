<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['budget', 'progress']);
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->decimal('budget', 15, 2)->nullable();
            $table->integer('progress')->default(0);
        });
    }
};