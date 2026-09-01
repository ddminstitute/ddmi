<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('grievances', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 20)->unique();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('reported_by')->nullable();
            $table->string('subject');
            $table->text('description');
            $table->enum('category', ['transaction','account','loan','service','staff','other'])->default('other');
            $table->enum('priority', ['low','medium','high','urgent'])->default('medium');
            $table->enum('status', ['open','in_progress','resolved','closed','escalated'])->default('open');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->date('sla_due_date')->nullable();
            $table->timestamps();
        });

        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number', 20)->unique();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->enum('request_type', ['stop_cheque','address_change','mobile_change','email_change','passbook_reissue','account_unfreeze','statement_request','nominee_change','other'])->default('other');
            $table->text('details')->nullable();
            $table->enum('status', ['pending','approved','rejected','completed'])->default('pending');
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('demand_drafts', function (Blueprint $table) {
            $table->id();
            $table->string('dd_number', 20)->unique();
            $table->unsignedBigInteger('account_id');
            $table->enum('instrument_type', ['demand_draft','pay_order'])->default('demand_draft');
            $table->string('payee_name');
            $table->string('payable_at_city')->nullable();
            $table->string('payable_at_bank')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('charges', 10, 2)->default(0);
            $table->decimal('total_debited', 15, 2);
            $table->date('issue_date');
            $table->date('valid_until')->nullable();
            $table->enum('status', ['active','encashed','cancelled','returned'])->default('active');
            $table->string('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->foreign('account_id')->references('id')->on('accounts');
        });

        Schema::create('eod_records', function (Blueprint $table) {
            $table->id();
            $table->date('business_date')->unique();
            $table->decimal('total_deposits', 15, 2)->default(0);
            $table->decimal('total_withdrawals', 15, 2)->default(0);
            $table->decimal('total_transfers', 15, 2)->default(0);
            $table->decimal('interest_posted', 15, 2)->default(0);
            $table->decimal('penalties_applied', 15, 2)->default(0);
            $table->unsignedInteger('accounts_count')->default(0);
            $table->unsignedInteger('transactions_count')->default(0);
            $table->unsignedInteger('emis_marked_overdue')->default(0);
            $table->enum('status', ['pending','processing','completed','failed'])->default('completed');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('branch_code', 10)->unique();
            $table->string('branch_name');
            $table->string('address')->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('pincode', 10)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('manager_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('accounts', 'branch_id')) {
                $table->unsignedBigInteger('branch_id')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grievances');
        Schema::dropIfExists('service_requests');
        Schema::dropIfExists('demand_drafts');
        Schema::dropIfExists('eod_records');
        Schema::dropIfExists('branches');
    }
};
