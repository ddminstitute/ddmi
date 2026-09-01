<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loan_guarantors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');
            $table->string('name');
            $table->string('relation', 50);
            $table->string('phone', 20);
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('id_proof_type', 50)->nullable();
            $table->string('id_proof_number', 50)->nullable();
            $table->string('id_proof_file')->nullable();
            $table->timestamps();
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
        });

        Schema::create('loan_collaterals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');
            $table->enum('collateral_type', ['gold','property','vehicle','fd','other']);
            $table->string('description');
            $table->decimal('estimated_value', 15, 2);
            $table->date('valuation_date')->nullable();
            $table->string('document_file')->nullable();
            $table->date('charge_created_date')->nullable();
            $table->date('charge_released_date')->nullable();
            $table->enum('status', ['active','released'])->default('active');
            $table->timestamps();
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
        });

        Schema::table('loans', function (Blueprint $table) {
            if (!Schema::hasColumn('loans', 'overdue_days')) { $table->unsignedInteger('overdue_days')->default(0)->after('outstanding_amount'); }
            if (!Schema::hasColumn('loans', 'penalty_amount')) { $table->decimal('penalty_amount', 15, 2)->default(0)->after('overdue_days'); }
            if (!Schema::hasColumn('loans', 'penal_rate')) { $table->decimal('penal_rate', 5, 2)->default(2.00)->after('penalty_amount'); }
            if (!Schema::hasColumn('loans', 'is_npa')) { $table->boolean('is_npa')->default(false)->after('penal_rate'); }
            if (!Schema::hasColumn('loans', 'npa_date')) { $table->date('npa_date')->nullable()->after('is_npa'); }
            if (!Schema::hasColumn('loans', 'foreclosure_date')) { $table->date('foreclosure_date')->nullable()->after('npa_date'); }
            if (!Schema::hasColumn('loans', 'foreclosure_amount')) { $table->decimal('foreclosure_amount', 15, 2)->nullable()->after('foreclosure_date'); }
            if (!Schema::hasColumn('loans', 'foreclosure_charges')) { $table->decimal('foreclosure_charges', 15, 2)->default(0)->after('foreclosure_amount'); }
            if (!Schema::hasColumn('loans', 'restructured_at')) { $table->timestamp('restructured_at')->nullable()->after('foreclosure_charges'); }
            if (!Schema::hasColumn('loans', 'restructure_reason')) { $table->string('restructure_reason')->nullable()->after('restructured_at'); }
        });

        Schema::table('emi_schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('emi_schedules', 'overdue_days')) { $table->unsignedInteger('overdue_days')->default(0)->after('status'); }
            if (!Schema::hasColumn('emi_schedules', 'penalty_amount')) { $table->decimal('penalty_amount', 15, 2)->default(0)->after('overdue_days'); }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_guarantors');
        Schema::dropIfExists('loan_collaterals');
    }
};
