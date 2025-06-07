<?php

namespace App\Jobs;

use App\Models\Task;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessPdfToImages extends ProcessUploadedFile
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function convertToLLM()
    {
        try {
            // Create a temporary directory and simulate image files
            $tempDir = sys_get_temp_dir() . '/pdf_images_' . uniqid();
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0777, true);
            }

            // Define the path to the PDF file
            $pdfPath = Storage::path($this->task->file_path);

            // Call ImageMagick (command line magick) to convert PDF to PNG images into $tempDir
            $command = [
                'magick',
                '-density', '1200',
                $pdfPath,
                '-background', 'white',
                '-alpha', 'remove',
                '-alpha', 'off',
                '-resize', 'x1500',
                "$tempDir/image-%d.png"
            ];
            $process = ($this->getProcessFactory())($command);
            $process->run();
        
            if (!$process->isSuccessful()) {
                throw new \RuntimeException('Image Magick conversion failed: ' . $process->getErrorOutput());
            }

            // Get the list of image files in the temporary directory
            $imageFiles = glob("$tempDir/*.png");

            // Encode each image file as base64 and store it in an array
            $imagesBase64 = [];
            foreach ($imageFiles as $imageFile) {
                $imagesBase64[] = base64_encode(file_get_contents($imageFile));
            }

            // Clean up temporary directory
            // array_map('unlink', glob("$tempDir/*"));
            // rmdir($tempDir);

            return $imagesBase64;
        } catch (\Exception $e) {
            throw new \RuntimeException('Magick service error: ' . $e->getMessage());
        }
    }

    protected function extractWithLLM($images): array
    {
        $prompt = file_get_contents(base_path("app/Jobs/prompt-markdown-to-json.txt"))
                . "\n\nInvoices are given in order as image attachments."
                . "\nCarefully examine every letter and punctation."
                . " Keep in mind that document origin is international and decimal places could be represented with comma (`,`) as well as dot (`.`)."
                . " Precision is very important, your text must match exactly what's seen in invoice image. Do not hallucinate.";
        $responseData = $this->llmCaller->callLLM($prompt, $images);
        return $this->parseOllamaResponse($responseData);
    }
}
