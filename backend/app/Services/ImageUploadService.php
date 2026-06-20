<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/**
 * Service terpusat untuk memproses upload gambar customer.
 *
 * Tanggung jawab:
 * - Validasi ukuran file maksimum 2MB (2.048 KB)
 * - Konversi format gambar apapun ke WebP sebelum disimpan
 * - Mendukung input dari: TemporaryUploadedFile (Livewire), base64 string, dan path string
 */
class ImageUploadService
{
    /**
     * Ukuran maksimum file yang diizinkan dalam bytes (2MB).
     */
    public const MAX_FILE_SIZE_BYTES = 2 * 1024 * 1024; // 2 MB

    /**
     * Kualitas kompresi WebP (0-100).
     */
    public const WEBP_QUALITY = 85;

    /**
     * Proses dan simpan gambar dari TemporaryUploadedFile (Livewire wire:model upload).
     *
     * @param  TemporaryUploadedFile  $file  File hasil upload Livewire
     * @param  string                 $directory  Direktori tujuan di storage/app/public
     * @return string  Path relatif file yang tersimpan (misal: customers/ktp/uuid.webp)
     * @throws \RuntimeException  Jika ukuran file melebihi 2MB
     */
    public function storeAsWebp(TemporaryUploadedFile $file, string $directory): string
    {
        // 1. Validasi ukuran file
        if ($file->getSize() > self::MAX_FILE_SIZE_BYTES) {
            throw new \RuntimeException('Ukuran file tidak boleh lebih dari 2MB.');
        }

        // 2. Baca konten file ke memori
        $imageContent = file_get_contents($file->getRealPath());

        // 3. Coba konversi ke WebP — jika gagal, simpan file asli (graceful degradation)
        $webpContent = $this->tryConvertToWebp($imageContent);

        if ($webpContent !== null) {
            // Berhasil konversi: simpan sebagai .webp
            $fileName     = Str::uuid()->toString() . '.webp';
            $relativePath = trim($directory, '/') . '/' . $fileName;
            Storage::disk('public')->put($relativePath, $webpContent);
        } else {
            // Fallback: simpan file asli dengan ekstensi aslinya
            $extension    = $file->getClientOriginalExtension() ?: 'jpg';
            $fileName     = Str::uuid()->toString() . '.' . $extension;
            $relativePath = trim($directory, '/') . '/' . $fileName;
            Storage::disk('public')->put($relativePath, $imageContent);
        }

        return $relativePath;
    }

    /**
     * Proses dan simpan gambar dari string base64 (misal: hasil kamera selfie browser).
     *
     * @param  string  $base64String  Data URI base64 (format: data:image/jpeg;base64,...)
     * @param  string  $directory     Direktori tujuan di storage/app/public
     * @return string  Path relatif file yang tersimpan
     * @throws \RuntimeException  Jika ukuran file melebihi 2MB
     */
    public function storeBase64AsWebp(string $base64String, string $directory): string
    {
        // 1. Ekstrak data biner dari base64
        $parts     = explode(';base64,', $base64String);
        $rawBinary = base64_decode($parts[1] ?? '');

        if ($rawBinary === false || strlen($rawBinary) === 0) {
            // Data tidak valid — simpan sebagai file kosong dengan ekstensi asli (edge case)
            $rawBinary = '';
        }

        // 2. Validasi ukuran
        if (strlen($rawBinary) > self::MAX_FILE_SIZE_BYTES) {
            throw new \RuntimeException('Ukuran gambar tidak boleh lebih dari 2MB.');
        }

        // 3. Coba konversi ke WebP — jika gagal, simpan file asli (graceful degradation)
        $webpContent = strlen($rawBinary) > 0 ? $this->tryConvertToWebp($rawBinary) : null;

        if ($webpContent !== null) {
            $fileName     = Str::uuid()->toString() . '.webp';
            $relativePath = trim($directory, '/') . '/' . $fileName;
            Storage::disk('public')->put($relativePath, $webpContent);
        } else {
            // Fallback: simpan file asli
            $typeInfo = explode(';', $parts[0] ?? 'image/jpeg');
            $mimeType = str_replace('data:', '', $typeInfo[0]);
            $ext      = explode('/', $mimeType)[1] ?? 'jpg';
            $fileName     = Str::uuid()->toString() . '.' . $ext;
            $relativePath = trim($directory, '/') . '/' . $fileName;
            Storage::disk('public')->put($relativePath, $rawBinary);
        }

        return $relativePath;
    }

    /**
     * Coba konversi binary image data ke format WebP.
     * Mengembalikan null jika konversi gagal (graceful degradation).
     *
     * @param  string  $imageContent  Konten biner gambar original
     * @return string|null  Konten biner WebP, atau null jika konversi gagal
     */
    protected function tryConvertToWebp(string $imageContent): ?string
    {
        try {
            return $this->convertToWebpBinary($imageContent);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Konversi binary image data ke format WebP menggunakan GD atau Imagick.
     *
     * @param  string  $imageContent  Konten biner gambar original
     * @return string  Konten biner gambar dalam format WebP
     * @throws \RuntimeException  Jika tidak ada ekstensi yang mendukung WebP atau konversi gagal
     */
    protected function convertToWebpBinary(string $imageContent): string
    {
        // Prioritas: GD extension (tersedia di hampir semua server shared hosting)
        if (extension_loaded('gd')) {
            return $this->convertWithGd($imageContent);
        }

        // Fallback: Imagick extension
        if (extension_loaded('imagick')) {
            return $this->convertWithImagick($imageContent);
        }

        throw new \RuntimeException(
            'Server tidak mendukung konversi gambar ke WebP. Pastikan ekstensi GD atau Imagick aktif.'
        );
    }

    /**
     * Konversi binary image ke WebP menggunakan ekstensi GD.
     *
     * @param  string  $imageContent  Konten biner gambar original
     * @return string  Konten biner WebP
     * @throws \RuntimeException  Jika GD gagal memproses gambar
     */
    protected function convertWithGd(string $imageContent): string
    {
        // Buat resource GD dari string biner
        $gdImage = @imagecreatefromstring($imageContent);

        if ($gdImage === false) {
            throw new \RuntimeException('Gagal memproses gambar. Pastikan file adalah gambar yang valid.');
        }

        // Pastikan transparansi tetap terjaga untuk PNG
        if (imageistruecolor($gdImage)) {
            imagepalettetotruecolor($gdImage);
            imagealphablending($gdImage, false);
            imagesavealpha($gdImage, true);
        }

        // Tangkap output WebP ke buffer
        ob_start();
        imagewebp($gdImage, null, self::WEBP_QUALITY);
        $webpContent = ob_get_clean();
        imagedestroy($gdImage);

        if ($webpContent === false || strlen($webpContent) === 0) {
            throw new \RuntimeException('Konversi gambar ke WebP gagal.');
        }

        return $webpContent;
    }

    /**
     * Konversi binary image ke WebP menggunakan ekstensi Imagick.
     *
     * @param  string  $imageContent  Konten biner gambar original
     * @return string  Konten biner WebP
     * @throws \RuntimeException  Jika Imagick gagal memproses gambar
     */
    protected function convertWithImagick(string $imageContent): string
    {
        if (!class_exists('Imagick')) {
            throw new \RuntimeException('Imagick extension not loaded.');
        }

        try {
            $imagickClass = 'Imagick';
            $imagick = new $imagickClass();
            $imagick->readImageBlob($imageContent);
            $imagick->setImageFormat('webp');
            $imagick->setImageCompressionQuality(self::WEBP_QUALITY);
            $imagick->stripImage(); // Hapus metadata EXIF untuk menghemat ukuran

            $webpContent = $imagick->getImageBlob();
            $imagick->destroy();

            return $webpContent;
        } catch (\Throwable $e) {
            throw new \RuntimeException('Imagick gagal memproses gambar: ' . $e->getMessage());
        }
    }

    /**
     * Hapus file lama dari storage jika path diberikan (untuk replace gambar).
     *
     * @param  string|null  $oldPath  Path relatif file lama di disk public
     * @return void
     */
    public function deleteOldFile(?string $oldPath): void
    {
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
    }
}
