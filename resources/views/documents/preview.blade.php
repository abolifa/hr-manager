@php
    use App\Helpers\CommonHelpers;
    $record = $getRecord();
    $attachments = $record->attachments ?? [];
    $pdfContent = CommonHelpers::mergePdfsInMemory($attachments);
    $base64 = base64_encode($pdfContent);
    $src = 'data:application/pdf;base64,' . $base64;
@endphp

<style>
    .iframe-container {
        width: 100%;
        height: 100vh;
    }

    .iframe-container iframe {
        width: 100%;
        height: 100%;
        border: 0;
    }
</style>

<div class="iframe-container">
    <iframe src="{{ $src }}#toolbar=1&navpanes=0&scrollbar=0"></iframe>
</div>
