<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recurring_deposits', function (Blueprint $table) {
            $table->id();
            $table->string('rd_number', 30)->unique();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('account_id');
            $table->decimal('monthly_installment', 15, 2);
            $table->decimal('interest_rate', 6, 2);
            $table->unsignedInteger('tenure_months');
            $table->date('start_date');
            $table->date('maturity_date');
            $table->decimal('total_deposited', 15, 2)->default(0);
            $table->decimal('interest_earned', 15, 2)->default(0);
            $table->decimal('maturity_amount', 15, 2);
            $table->unsignedInteger('installments_paid')->default(0);
            $table->unsignedInteger('installments_missed')->default(0);
            $table->enum('status', ['active','matured','closed','defaulted'])->default('active');
            $table->date('next_due_date');
            $table->timestamp('matured_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('restrict');
        });

        Schema::create('rd_installments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rd_id');
            $table->unsignedInteger('installment_number');
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending','paid','missed'])->default('pending');
            $table->string('reference_number', 30)->nullable();
            $table->timestamps();
            $table->foreign('rd_id')->references('id')->on('recurring_deposits')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rd_installments');
        Schema::dropIfExists('recurring_deposits');
    }
};
