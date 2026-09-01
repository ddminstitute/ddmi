<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cheques', function (Blueprint $table) {
            $table->id();
            $table->string('cheque_number', 20);
            $table->unsignedBigInteger('account_id');
            $table->enum('cheque_type', ['issued','received'])->default('received');
            $table->string('drawee_bank')->nullable();
            $table->string('drawee_branch')->nullable();
            $table->string('drawer_name')->nullable();
            $table->decimal('amount', 15, 2);
            $table->date('cheque_date');
            $table->date('deposit_date')->nullable();
            $table->date('clearing_date')->nullable();
            $table->enum('status', ['pending','cleared','bounced','cancelled'])->default('pending');
            $table->string('bounce_reason')->nullable();
            $table->decimal('bounce_charge', 10, 2)->default(0);
            $table->string('description')->nullable();
            $table->string('reference_number', 30)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->foreign('account_id')->references('id')->on('accounts');
        });

        Schema::create('fund_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 30)->unique();
            $table->unsignedBigInteger('account_id');
            $table->enum('transfer_mode', ['neft','rtgs','imps','upi','internal'])->default('neft');
            $table->decimal('amount', 15, 2);
            $table->string('beneficiary_name');
            $table->string('beneficiary_account', 25);
            $table->string('beneficiary_ifsc', 12);
            $table->string('beneficiary_bank')->nullable();
            $table->string('description')->nullable();
            $table->enum('status', ['pending','processing','completed','failed','reversed'])->default('pending');
            $table->string('bank_reference')->nullable();
            $table->timestamp('initiated_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('failure_reason')->nullable();
            $table->decimal('charges', 10, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->foreign('account_id')->references('id')->on('accounts');
        });

        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'is_reversed')) { $table->boolean('is_reversed')->default(false)->after('status'); }
            if (!Schema::hasColumn('transactions', 'reversed_by')) { $table->unsignedBigInteger('reversed_by')->nullable()->after('is_reversed'); }
            if (!Schema::hasColumn('transactions', 'reversed_at')) { $table->timestamp('reversed_at')->nullable()->after('reversed_by'); }
            if (!Schema::hasColumn('transactions', 'reversal_reason')) { $table->string('reversal_reason')->nullable()->after('reversed_at'); }
            if (!Schema::hasColumn('transactions', 'parent_transaction_id')) { $table->unsignedBigInteger('parent_transaction_id')->nullable()->after('reversal_reason'); }
            if (!Schema::hasColumn('transactions', 'transaction_mode')) { $table->enum('transaction_mode', ['cash','cheque','neft','rtgs','imps','upi','internal'])->default('cash')->after('transaction_type'); }
        });

        Schema::create('standing_instructions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->enum('instruction_type', ['transfer','emi_payment','utility','rd_installment'])->default('transfer');
            $table->unsignedBigInteger('to_account_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->enum('frequency', ['weekly','monthly','quarterly','yearly'])->default('monthly');
            $table->unsignedTinyInteger('execution_day')->default(1);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('last_executed_date')->nullable();
            $table->date('next_execution_date');
            $table->unsignedInteger('executed_count')->default(0);
            $table->enum('status', ['active','paused','cancelled','completed'])->default('active');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->foreign('account_id')->references('id')->on('accounts');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cheques');
        Schema::dropIfExists('fund_transfers');
        Schema::dropIfExists('standing_instructions');
    }
};
