<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fixed_deposits', function (Blueprint $table) {
            $table->id();
            $table->string('fd_number', 30)->unique();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('account_id');
            $table->decimal('principal_amount', 15, 2);
            $table->decimal('interest_rate', 6, 2);
            $table->enum('compounding', ['monthly','quarterly','half_yearly','yearly','on_maturity'])->default('quarterly');
            $table->unsignedInteger('tenure_months');
            $table->date('start_date');
            $table->date('maturity_date');
            $table->decimal('maturity_amount', 15, 2);
            $table->decimal('interest_earned', 15, 2)->default(0);
            $table->enum('status', ['active','matured','closed','premature_closed'])->default('active');
            $table->boolean('auto_renew')->default(false);
            $table->decimal('premature_penalty_percent', 5, 2)->default(1.00);
            $table->timestamp('matured_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->string('closure_reason')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('restrict');
        });
    }

    public function down(): void { Schema::dropIfExists('fixed_deposits'); }
};
