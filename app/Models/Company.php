<?php

namespace App\Models;

use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static find($state)
 */
class Company extends Model
{
    /** @use HasFactory<CompanyFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'english_name',
        'law_shape',
        'phone',
        'email',
        'ceo',
        'members',
        'capital',
        'established_at',
        'address',
        'logo',
        'letterhead',
    ];

    protected $casts = [
        'members' => 'array',
        'established_at' => 'date',
    ];


    public function accounts(): HasMany
    {
        return $this->hasMany(BankAccount::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function incomings(): HasMany
    {
        return $this->hasMany(Incoming::class);
    }

    public function outgoings(): HasMany
    {
        return $this->hasMany(Outgoing::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }
}
