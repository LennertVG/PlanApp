<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('level')->addColumn();
            $table->integer('xp')->addColumn();
            $table->integer('streakCount')->addColumn();
            $table->integer('group_id')->addColumn();
            $table->string('avatar')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
