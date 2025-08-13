<?php

namespace App\Services;

use App\Mail\GenericDocumentMail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class DocumentMailer
{
    public static function sendEmail(string|array $to, string $subject, string $html, array $attachments = [], array $options = []): void
    {
        $to = collect(Arr::wrap($to))
            ->filter(fn($e) => !empty($e))
            ->unique()
            ->values()
            ->all();

        $mailable = new GenericDocumentMail($subject, $html, $attachments);

        if (!empty($options['from'])) {
            $mailable->from($options['from']['address'], $options['from']['name'] ?? null);
        }
        if (!empty($options['cc'])) $mailable->cc(Arr::wrap($options['cc']));
        if (!empty($options['bcc'])) $mailable->bcc(Arr::wrap($options['bcc']));
        if (!empty($options['replyTo'])) $mailable->replyTo(Arr::wrap($options['replyTo']));

        $queue = $options['queue'] ?? true;

        if ($queue) {
            Mail::to($to)->queue($mailable);
        } else {
            Mail::to($to)->send($mailable);
        }
    }
}
