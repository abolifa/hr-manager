<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use App\Helpers\CommonHelpers;
use Filament\Resources\Pages\CreateRecord;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['attachments'] = $this->convertAttachmentsToPdf($data['attachments'] ?? []);
        return $data;
    }

    private function convertAttachmentsToPdf(array $attachments): array
    {
        $converted = [];

        foreach ($attachments as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                // Convert image to PDF
                $file = CommonHelpers::convertImageToPdf($file);
            }

            $converted[] = $file;
        }

        return $converted;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['attachments'] = $this->convertAttachmentsToPdf($data['attachments'] ?? []);
        return $data;
    }
}
