<?php

namespace App\Http\Controllers;

use App\Models\Media;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\Settings;
use Illuminate\Support\Facades\File;

class DokumenController extends Controller
{
    public function preview($id)
    {
        $media = Media::findOrFail($id);
        $extension = strtolower(pathinfo($media->file, PATHINFO_EXTENSION));
        
        $filePath = public_path($media->path);

        if (!file_exists($filePath)) {
            return abort(404, 'File not found');
        }

        $tempDir = storage_path('app/temp');
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        if ($extension === 'pdf') {
            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $media->name . '.pdf"'
            ]);
        }

        if (in_array($extension, ['doc', 'docx', 'xls', 'xlsx'])) {
            try {
                $tempFile = $tempDir . '/' . uniqid() . '.pdf';
                
                if ($extension === 'doc' || $extension === 'docx') {
                    $phpWord = WordIOFactory::load($filePath);
                    
                    $htmlWriter = WordIOFactory::createWriter($phpWord, 'HTML');
                    $html = '';
                    ob_start();
                    $htmlWriter->save('php://output');
                    $html = ob_get_clean();
                    
                    $dompdf = new Dompdf([
                        'chroot' => [
                            base_path(),
                            public_path(),
                            storage_path(),
                        ],
                        'isRemoteEnabled' => true,
                        'isHtml5ParserEnabled' => true,
                        'isPhpEnabled' => true,
                        'tempDir' => $tempDir,
                    ]);
                    
                    $dompdf->loadHtml($html);
                    $dompdf->setPaper('A4', 'portrait');
                    
                    $dompdf->render();
                    
                    file_put_contents($tempFile, $dompdf->output());
                } else {
                    $spreadsheet = IOFactory::load($filePath);
                    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($spreadsheet);
                    $writer->save($tempFile);
                }

                if (!file_exists($tempFile)) {
                    throw new \Exception('Failed to create PDF file');
                }

                return response()->file($tempFile, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $media->name . '.pdf"'
                ])->deleteFileAfterSend(true);
            } catch (\Exception $e) {
                Log::error('Document conversion failed: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
                return response()->json([
                    'error' => 'Failed to convert file: ' . $e->getMessage(),
                    'details' => [
                        'extension' => $extension,
                        'tempDir' => $tempDir,
                        'exists' => File::exists($tempDir)
                    ]
                ], 500);
            }
        }

        return abort(404, 'Unsupported file type');
    }
} 