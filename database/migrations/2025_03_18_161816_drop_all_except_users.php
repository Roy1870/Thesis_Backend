<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $exceptTables = ['users', 'user_profiles', 'password_reset_tokens', 'personal_access_tokens', 'failed_jobs','migrations'];

        // Get all table names except the ones we want to keep (PostgreSQL version)
        $tables = DB::select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");

        foreach ($tables as $table) {
            $tableName = $table->tablename;
            if (!in_array($tableName, $exceptTables)) {
                // Use CASCADE to drop tables along with dependent foreign keys
                DB::statement("DROP TABLE IF EXISTS \"$tableName\" CASCADE");
                echo "Dropped table: $tableName\n"; // Optional: Output dropped tables
            }
        }
    }

    public function down(): void
    {
        // No rollback, because we are deleting tables
    }
};
