<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nominees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->string('name');
            $table->string('relation', 50);
            $table->date('date_of_birth')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('address')->nullable();
            $table->unsignedTinyInteger('share_percent')->default(100);
            $table->boolean('is_minor')->default(false);
            $table->string('guardian_name')->nullable();
            $table->timestamps();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });

        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'pan_number')) {
                $table->string('pan_number', 20)->nullable()->after('email');
            }
            if (!Schema::hasColumn('customers', 'kyc_status')) {
                $table->enum('kyc_status', ['pending','verified','rejected','expired'])->default('pending')->after('pan_number');
            }
            if (!Schema::hasColumn('customers', 'kyc_verified_at')) {
                $table->timestamp('kyc_verified_at')->nullable()->after('kyc_status');
            }
            if (!Schema::hasColumn('customers', 'kyc_verified_by')) {
                $table->unsignedBigInteger('kyc_verified_by')->nullable()->after('kyc_verified_at');
            }
            if (!Schema::hasColumn('customers', 'kyc_remarks')) {
                $table->string('kyc_remarks')->nullable()->after('kyc_verified_by');
            }
            if (!Schema::hasColumn('customers', 'kyc_expiry_date')) {
                $table->date('kyc_expiry_date')->nullable()->after('kyc_remarks');
            }
        });

        Schema::table('accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('accounts', 'daily_withdrawal_limit')) {
                $table->decimal('daily_withdrawal_limit', 15, 2)->nullable()->after('balance');
            }
            if (!Schema::hasColumn('accounts', 'daily_transfer_limit')) {
                $table->decimal('daily_transfer_limit', 15, 2)->nullable()->after('daily_withdrawal_limit');
            }
            if (!Schema::hasColumn('accounts', 'closure_requested_at')) {
                $table->timestamp('closure_requested_at')->nullable()->after('daily_transfer_limit');
            }
            if (!Schema::hasColumn('accounts', 'closure_reason')) {
                $table->string('closure_reason')->nullable()->after('closure_requested_at');
            }
            if (!Schema::hasColumn('accounts', 'closed_at')) {
                $table->timestamp('closed_at')->nullable()->after('closure_reason');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nominees');
    }
};
