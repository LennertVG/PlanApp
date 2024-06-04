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
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('user_id')->addColumn();
            $table->string('description')->nullable()->addColumn();
            $table->string('evidencePath')->nullable()->addColumn();
            $table->integer('createdBy')->addColumn();
            $table->dateTime('submit_at')->nullable()->addColumn();
            $table->integer('completed')->addColumn();
            //delete certain columns
            $table->dropColumn(['amount', 'city']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
