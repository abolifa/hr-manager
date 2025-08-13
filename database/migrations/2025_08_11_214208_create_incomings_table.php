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
        Schema::create('incomings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('internal_number')->unique();
            $table->foreignId('from_recipient_id')->nullable()->constrained('recipients')->nullOnDelete();
            $table->string('from_name')->nullable();
            $table->date('received_date')->nullable();
            $table->string('notes')->nullable();
            $table->json('attachments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomings');
    }
};
