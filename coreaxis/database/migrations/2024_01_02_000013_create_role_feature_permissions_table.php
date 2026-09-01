<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('role_feature_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('feature_key');
            $table->string('role');
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
            $table->unique(['feature_key', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_feature_permissions');
    }
};
