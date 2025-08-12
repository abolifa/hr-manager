<?php

namespace App\Models;

use Database\Factories\IncomingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incoming extends Model
{
    /** @use HasFactory<IncomingFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'internal_number',
        'from_recipient_id',
        'received_date',
        'notes',
        'attachments',
    ];

    protected $casts = [
        'received_date' => 'date',
        'attachments' => 'array',
    ];

    public function fromRecipient(): BelongsTo
    {
        return $this->belongsTo(Recipient::class, 'from_recipient_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

}
