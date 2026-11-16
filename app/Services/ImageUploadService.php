<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ImageUploadService
{
    public function storeBase64Webp(
        string $dataUri,
        string $folder,
        string $year,
        string $prefix,
        ?string $oldFile = null,
        int $maxDimension = 1200,
    ): string {
        if (! preg_match('/^data:image\/(jpeg|jpg|png|webp);base64,([A-Za-z0-9+\/=\s]+)$/i', $dataUri, $matches)) {
            throw ValidationException::withMessages(['image' => 'รองรับเฉพาะรูป JPG, PNG และ WebP เท่านั้น ไม่รองรับ GIF']);
        }

        $binary = base64_decode(str_replace(' ', '+', $matches[2]), true);
        if ($binary === false || strlen($binary) > 10 * 1024 * 1024) {
            throw ValidationException::withMessages(['image' => 'ไฟล์รูปไม่ถูกต้องหรือมีขนาดเกิน 10 MB']);
        }

        $source = @imagecreatefromstring($binary);
        if (! $source) {
            throw ValidationException::withMessages(['image' => 'ไม่สามารถอ่านไฟล์รูปได้']);
        }

        $width = imagesx($source);
        $height = imagesy($source);
        $scale = min(1, $maxDimension / max($width, $height));
        $targetWidth = max(1, (int) round($width * $scale));
        $targetHeight = max(1, (int) round($height * $scale));
        $target = imagecreatetruecolor($targetWidth, $targetHeight);
        imagealphablending($target, false);
        imagesavealpha($target, true);
        $transparent = imagecolorallocatealpha($target, 0, 0, 0, 127);
        imagefilledrectangle($target, 0, 0, $targetWidth, $targetHeight, $transparent);
        imagecopyresampled($target, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

        ob_start();
        imagewebp($target, null, 82);
        $webp = ob_get_clean();
        imagedestroy($source);
        imagedestroy($target);

        if (! is_string($webp) || $webp === '') {
            throw ValidationException::withMessages(['image' => 'ไม่สามารถแปลงรูปเป็น WebP ได้']);
        }

        $fileName = $prefix.'_'.now()->format('YmdHis').'_'.bin2hex(random_bytes(3)).'.webp';
        Storage::disk('public')->put(storage_year_path($folder, $fileName, $year), $webp);

        if ($oldFile) {
            delete_storage_file($folder, $oldFile, $year);
        }

        return $fileName;
    }
}
