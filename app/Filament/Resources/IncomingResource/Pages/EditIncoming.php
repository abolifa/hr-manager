<?php

namespace App\Filament\Resources\IncomingResource\Pages;

use App\Filament\Resources\IncomingResource;
use App\Helpers\CommonHelpers;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIncoming extends EditRecord
{
    protected static string $resource = IncomingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

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
