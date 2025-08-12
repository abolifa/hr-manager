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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('english_name')->index()->nullable();
            $table->string('law_shape')->nullable();
            $table->string('phone')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('ceo')->nullable();
            $table->json('members')->nullable();
            $table->decimal('capital', 15, 2)->nullable();
            $table->date('established_at')->nullable();
            $table->string('address')->nullable();

            $table->string('logo')->nullable();
            $table->string('letterhead')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
