<?php
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        // SQLite stores enums as text, so no schema change needed.
        // super_admin is a valid role value used at application level.
    }

    public function down(): void {}
};
