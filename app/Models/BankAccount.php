<?php

namespace App\Models;

use Database\Factories\BankAccountFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static distinct(string $string)
 */
class BankAccount extends Model
{
    /** @use HasFactory<BankAccountFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'account_number',
        'bank_name',
        'branch_name',
        'account_holder_name',
        'swift_code',
        'balance',
        'currency',
        'account_type',
        'active',
        'notes',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
