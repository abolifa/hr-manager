<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('document_number')->nullable();
            $table->enum('type', [
                'commercial_registration',
                'business_license',
                'industrial_license',
                'importers_register',
                'statistical_code',
                'authorized_economic_operator',
                'tax_clearance',
                'balance_sheet',
                'social_security_clearance',
                'partnership_certificate',
                'articles_of_incorporation',
                'amendment_contract',
                'company_bylaws',
                'chamber_of_commerce_membership',
                'customs_registration',
                'tax_id_certificate',
                'environmental_compliance',
                'industrial_safety',
                'municipality_permit',
                'insurance_policy',
                'bank_solvency_certificate',
                'power_of_attorney',
                'trademark_registration',
                'export_license',
                'vat_registration',
                'else',
            ])->index();
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->json('attachments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
