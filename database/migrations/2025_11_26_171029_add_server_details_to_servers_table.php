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
        Schema::table('servers', function (Blueprint $table) {
            $table->json('server_details')->nullable()->after('connection_options');
            $table->decimal('cpu_usage', 5, 2)->nullable()->after('server_details');
            $table->string('memory_usage')->nullable()->after('cpu_usage');
            $table->string('disk_usage')->nullable()->after('memory_usage');
            $table->string('os_info')->nullable()->after('disk_usage');
            $table->string('kernel_version')->nullable()->after('os_info');
            $table->string('uptime')->nullable()->after('kernel_version');
            $table->timestamp('last_detail_fetch_at')->nullable()->after('last_connected_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn([
                'server_details',
                'cpu_usage',
                'memory_usage',
                'disk_usage',
                'os_info',
                'kernel_version',
                'uptime',
                'last_detail_fetch_at',
            ]);
        });
    }
};
