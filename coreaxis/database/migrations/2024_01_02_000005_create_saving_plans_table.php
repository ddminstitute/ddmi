<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('saving_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_code')->unique();
            $table->string('plan_name');
            $table->enum('plan_type', ['daily', 'weekly', 'monthly', 'yearly']);
            $table->decimal('minimum_amount', 15, 2)->default(0);
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->integer('tenure_months')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('saving_plans'); }
};
