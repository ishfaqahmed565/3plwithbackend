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
            $table->unsignedBigInteger('received_by_agent_id')->nullable()->after('scan1_at');
            $table->foreign('received_by_agent_id')->references('id')->on('agents')->onDelete('set null');
            $table->tinyInteger('received_in_warehouse')->nullable()->after('received_by_agent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropForeign(['received_by_agent_id']);
            $table->dropColumn(['received_by_agent_id', 'received_in_warehouse']);
        });
    }
};
