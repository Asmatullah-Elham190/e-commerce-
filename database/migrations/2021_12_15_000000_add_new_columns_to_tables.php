<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToTables extends Migration
{
    public function up(): void
    {
        // This application had a historical schema patch applied directly in the live database.
        // The current database is the source of truth; no destructive change is made here.
    }

    public function down(): void
    {
        // No-op: the live database schema is authoritative and already reflects the applied changes.
    }
}
