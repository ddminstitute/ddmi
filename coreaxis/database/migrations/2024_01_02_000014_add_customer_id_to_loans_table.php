<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('loans', function (Blueprint $table) {
            if (!Schema::hasColumn('loans', 'customer_id')) {
                $table->unsignedBigInteger('customer_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('loans', 'amount')) {
                $table->decimal('amount', 15, 2)->default(0)->after('customer_id');
            }
            if (!Schema::hasColumn('loans', 'emi_amount')) {
                $table->decimal('emi_amount', 15, 2)->default(0)->after('monthly_emi');
            }
        });
    }
    public function down(): void {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(array_filter(['customer_id', 'amount', 'emi_amount'], fn($c) => Schema::hasColumn('loans', $c)));
        });
    }
};
