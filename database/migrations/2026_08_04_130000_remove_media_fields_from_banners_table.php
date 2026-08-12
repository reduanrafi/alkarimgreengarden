<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('banners', 'media_type')) {
                $columns[] = 'media_type';
            }
            if (Schema::hasColumn('banners', 'video_path')) {
                $columns[] = 'video_path';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('media_type')->default('image')->after('image');
            $table->string('video_path')->nullable()->after('media_type');
        });
    }
};
