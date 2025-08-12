<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('account_number')->unique();
            $table->string('bank_name');
            $table->string('branch_name')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('swift_code')->nullable();
            $table->enum('currency', ['LYD', 'USD', 'EUR', 'GBP', 'AED'])->default('LYD');
            $table->enum('account_type', ['normal', 'card', 'other'])->default('normal');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
