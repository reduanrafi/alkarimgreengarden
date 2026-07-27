<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('type')->default('hero_banner')->after('title');
            $table->integer('display_order')->default(0)->after('type');
            $table->string('button_text')->nullable()->after('image');
            $table->string('redirect_url')->nullable()->after('button_text');
            $table->date('start_date')->nullable()->after('redirect_url');
            $table->date('end_date')->nullable()->after('start_date');
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['type', 'display_order', 'button_text', 'redirect_url', 'start_date', 'end_date']);
        });
    }
};
