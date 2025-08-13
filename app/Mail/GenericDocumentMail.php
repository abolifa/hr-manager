<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericDocumentMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        protected string $subjectLine,
        protected string $htmlContent,
        protected array  $docAttachments = []
    )
    {
    }


    public function build(): self
    {
        $email = $this->subject($this->subjectLine)->html($this->htmlContent);

        foreach ($this->docAttachments as $att) {
            if (isset($att['content'])) {
                $content = $att['content'];
                if (($att['base64'] ?? false) === true) {
                    $content = base64_decode($content);
                }
                $email->attachData($content, $att['as'] ?? 'document', [
                    'mime' => $att['mime'] ?? 'application/octet-stream',
                ]);
                continue;
            }

            // path/disk attachments
            $email->attachFromStorageDisk(
                $att['disk'] ?? 'public',
                $att['path'] ?? '',
                $att['as'] ?? null,
                ['mime' => $att['mime'] ?? null]
            );
        }

        return $email;
    }
}
