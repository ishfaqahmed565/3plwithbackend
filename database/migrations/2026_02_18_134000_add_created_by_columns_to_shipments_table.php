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
        Schema::table('shipments', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_agent_id')->nullable()->after('client_id');
            $table->foreign('created_by_agent_id')->references('id')->on('agents')->onDelete('set null');
            $table->unsignedBigInteger('created_by_admin_id')->nullable()->after('created_by_agent_id');
            $table->foreign('created_by_admin_id')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropForeign(['created_by_agent_id']);
            $table->dropForeign(['created_by_admin_id']);
            $table->dropColumn(['created_by_agent_id', 'created_by_admin_id']);
        });
    }
};
