<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('collection_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_number')->unique();
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->unsignedBigInteger('account_id')->nullable();
            $table->foreign('account_id')->references('id')->on('accounts')->nullOnDelete();
            $table->string('plan_name');
            $table->enum('collection_type', ['daily', 'weekly', 'monthly']);
            $table->decimal('collection_amount', 15, 2);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('total_installments')->nullable();
            $table->decimal('maturity_amount', 15, 2)->nullable();
            $table->enum('status', ['active', 'completed', 'closed'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('collection_plans'); }
};
