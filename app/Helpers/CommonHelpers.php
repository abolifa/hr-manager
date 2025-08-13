<?php

namespace App\Helpers;

use App\Models\Incoming;
use App\Models\Outgoing;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Filter\FilterException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use setasign\Fpdi\PdfReader\PdfReaderException;
use Throwable;

class CommonHelpers
{
    public static function convertImageToPdf(string $path): string
    {
        $fullPath = Storage::disk('public')->path($path);
        $pdf = new Fpdi();
        $pdf->AddPage();
        [$width, $height] = getimagesize($fullPath);
        $a4Width = 210;
        $a4Height = 297;
        $ratio = min($a4Width / $width, $a4Height / $height);
        $newWidth = $width * $ratio;
        $newHeight = $height * $ratio;
        $x = ($a4Width - $newWidth) / 2;
        $y = ($a4Height - $newHeight) / 2;
        $pdf->Image($fullPath, $x, $y, $newWidth, $newHeight);
        $pdfPath = preg_replace('/\.(jpg|jpeg|png|gif|webp)$/i', '.pdf', $path);
        Storage::disk('public')->put($pdfPath, $pdf->Output('S'));
        Storage::disk('public')->delete($path);
        return $pdfPath;
    }

    /**
     * @throws CrossReferenceException
     * @throws PdfReaderException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws FilterException
     */

    public static function mergePdfsInMemory(array $paths): string
    {
        $pdf = new Fpdi();
        foreach ($paths as $path) {
            $fullPath = Storage::disk('public')->path($path);
            if (!file_exists($fullPath)) {
                continue;
            }
            $pageCount = $pdf->setSourceFile($fullPath);
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $tplId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($tplId);
                $orientation = $size['width'] > $size['height'] ? 'L' : 'P';
                $pdf->AddPage($orientation, [$size['width'], $size['height']]);
                $pdf->useTemplate($tplId);
            }
        }
        return $pdf->Output('S');
    }


    public static function nextIssueNumber(): string
    {
        $year = now()->year;
        $last = Outgoing::query()
            ->where('issue_number', 'like', '%-' . $year)
            ->latest('id')
            ->value('issue_number');

        if ($last && preg_match('/^(\d+)-' . $year . '$/', $last, $m)) {
            $next = (int)$m[1] + 1;
        } else {
            $next = 1;
        }
        return "$next-$year";
    }

    public static function nextIncomingNumber(): string
    {
        $year = now()->year;
        $last = Incoming::query()
            ->where('internal_number', 'like', '%-' . $year)
            ->latest('id')
            ->value('internal_number');

        if ($last && preg_match('/^(\d+)-' . $year . '$/', $last, $m)) {
            $next = (int)$m[1] + 1;
        } else {
            $next = 1;
        }
        return "$next-$year";
    }


    public static function downloadOutgoing($id): Response
    {
        $outgoing = Outgoing::with(['company', 'recipient'])->findOrFail($id);
        $letterheadPath = optional($outgoing->company)->letterhead
            ? public_path('storage/' . ltrim($outgoing->company->letterhead, '/'))
            : null;
        if ($letterheadPath && file_exists($letterheadPath)) {
            $mime = mime_content_type($letterheadPath);
            if ($mime === 'image/webp') {
                try {
                    $img = imagecreatefromwebp($letterheadPath);
                    $tmp = storage_path('app/letterhead_for_mpdf.png');
                    imagepng($img, $tmp);
                    imagedestroy($img);
                    $letterheadForMpdf = $tmp;
                } catch (Throwable $e) {
                    $letterheadForMpdf = null;
                    Log::error('Error processing letterhead image: ' . $e->getMessage());
                }
            } else {
                $letterheadForMpdf = $letterheadPath;
            }
        }
        $data = [
            'issue_number' => $outgoing->issue_number,
            'issue_date' => $outgoing->issue_date,
            'receiver' => $outgoing->to,
            'title' => $outgoing->title,
            'body' => $outgoing->body,
            'ceo_name' => optional($outgoing->company)->ceo,
            'letterhead_data_url' => null,
        ];

        $config = [
            'format' => 'A4',
            'orientation' => 'P',
            'margin_top' => 55,
            'margin_right' => 20,
            'margin_bottom' => 40,
            'margin_left' => 20,
            'mode' => 'utf-8',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'dpi' => 100,
            'instanceConfigurator' => function ($mpdf) use ($letterheadForMpdf) {
                if ($letterheadForMpdf && file_exists($letterheadForMpdf)) {
                    $mpdf->SetWatermarkImage($letterheadForMpdf, 1.0, [210, 297], 0, 0);
                    $mpdf->showWatermarkImage = true;
                    $mpdf->watermarkImgBehind = true;
                }
            },
        ];

        $pdf = Pdf::loadView('outgoing.print', $data, [], $config);
        $content = $pdf->output();
        $path = "outgoings/outgoing-$outgoing->issue_number.pdf";
        Storage::disk('public')->put($path, $content);
        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"outgoing-$outgoing->issue_number.pdf\"",
        ]);
    }

}
