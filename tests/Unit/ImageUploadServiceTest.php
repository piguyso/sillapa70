<?php

namespace Tests\Unit;

use App\Services\ImageUploadService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ImageUploadServiceTest extends TestCase
{
    public function test_it_converts_a_png_data_uri_to_webp(): void
    {
        if (! function_exists('imagewebp')) {
            $this->markTestSkipped('GD WebP support is unavailable.');
        }

        Storage::fake('public');
        $image = imagecreatetruecolor(4, 4);
        ob_start();
        imagepng($image);
        $png = ob_get_clean();
        imagedestroy($image);

        $file = app(ImageUploadService::class)->storeBase64Webp(
            'data:image/png;base64,'.base64_encode($png),
            'picture',
            '2570',
            'student_1'
        );

        $this->assertStringEndsWith('.webp', $file);
        Storage::disk('public')->assertExists(storage_year_path('picture', $file, '2570'));
    }

    public function test_it_rejects_gif_data_uris(): void
    {
        $this->expectException(ValidationException::class);

        app(ImageUploadService::class)->storeBase64Webp(
            'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==',
            'picture',
            '2570',
            'student_1'
        );
    }
}
