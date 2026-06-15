<?php
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        // SQLite stores enum as text, so just updating validation rules is sufficient
        // No schema change needed for SQLite
    }

    public function down(): void {}
};
