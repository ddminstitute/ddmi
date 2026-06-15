<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('loan_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->integer('payment_number');
            $table->decimal('amount', 15, 2);
            $table->decimal('principal_component', 15, 2);
            $table->decimal('interest_component', 15, 2);
            $table->decimal('outstanding_after', 15, 2);
            $table->date('payment_date');
            $table->enum('status', ['paid', 'pending'])->default('paid');
            $table->string('reference_number', 30)->unique();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('loan_payments'); }
};
