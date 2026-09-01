<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->string('type')->comment('asset,liability,equity,income,expense');
            $table->string('normal_balance')->comment('debit,credit');
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false)->comment('system accounts cannot be deleted');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_number', 20)->unique();
            $table->date('entry_date');
            $table->string('narration');
            $table->string('source_type')->nullable()->comment('transaction,loan,fd,rd');
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('reference')->nullable();
            $table->decimal('total_debit', 15, 2)->default(0);
            $table->decimal('total_credit', 15, 2)->default(0);
            $table->boolean('is_balanced')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->index(['source_type','source_id']);
        });

        Schema::create('journal_entry_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('chart_of_account_id')->constrained();
            $table->string('type')->comment('debit,credit');
            $table->decimal('amount', 15, 2);
            $table->string('narration')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_entry_lines');
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('chart_of_accounts');
    }
};
