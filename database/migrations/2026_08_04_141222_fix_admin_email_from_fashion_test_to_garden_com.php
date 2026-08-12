<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // If admin@garden.com already exists (e.g. from seeder), remove the old record
        $newExists = DB::table('users')->where('email', 'admin@garden.com')->exists();

        if ($newExists) {
            DB::table('users')
                ->where('email', 'admin@fashion.test')
                ->delete();
        } else {
            DB::table('users')
                ->where('email', 'admin@fashion.test')
                ->update(['email' => 'admin@garden.com']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: cannot reliably restore a deleted record
    }
};
