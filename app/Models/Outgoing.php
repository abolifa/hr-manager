<?php

namespace App\Models;

use Database\Factories\OutgoingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Outgoing extends Model
{
    /** @use HasFactory<OutgoingFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'recipient_id',
        'issue_number',
        'issue_date',
        'to',
        'title',
        'body',
        'attachments',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'attachments' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(Recipient::class);
    }
}
