<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->integer('month');
            $table->integer('year');
            $table->integer('working_days');
            $table->integer('present_days');
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('hra', 15, 2)->default(0);
            $table->decimal('other_allowance', 15, 2)->default(0);
            $table->decimal('gross_salary', 15, 2);
            $table->decimal('deductions', 15, 2)->default(0);
            $table->decimal('net_salary', 15, 2);
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'generated', 'paid'])->default('generated');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('payslips'); }
};
