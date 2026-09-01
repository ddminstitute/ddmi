<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('collection_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collection_plan_id');
            $table->foreign('collection_plan_id')->references('id')->on('collection_plans');
            $table->decimal('amount', 15, 2);
            $table->date('collection_date');
            $table->integer('installment_number');
            $table->string('receipt_number')->unique();
            $table->enum('payment_mode', ['cash', 'upi', 'bank_transfer', 'cheque'])->default('cash');
            $table->string('collected_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('collection_entries'); }
};
