<?php

namespace App\Models;

use Database\Factories\RecipientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static find($state)
 */
class Recipient extends Model
{
    /** @use HasFactory<RecipientFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'is_government',
        'active',
    ];

    protected $casts = [
        'is_government' => 'boolean',
        'active' => 'boolean',
    ];

    public function outgoings(): HasMany
    {
        return $this->hasMany(Outgoing::class);
    }

    public function incomings(): HasMany
    {
        return $this->hasMany(Incoming::class, 'from_recipient_id');
    }
}
