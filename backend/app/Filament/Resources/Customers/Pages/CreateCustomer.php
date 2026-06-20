<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use App\Models\User;
use App\Services\ImageUploadService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    protected function afterCreate(): void
    {
        $this->record->assignRole('customer');
    }

    /**
     * Konversi semua gambar yang diupload ke format WebP sebelum data disimpan.
     * Filament FileUpload menyimpan file sementara di temporary path, lalu
     * memindahkannya ke direktori tujuan saat form di-submit. Di sini kita
     * intersep path akhir dan buat ulang file dalam format WebP.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $imageService = app(ImageUploadService::class);
        $imageFields  = [
            'selfie_image'  => 'customers/selfies',
            'ktp_image'     => 'customers/identities',
            'sim_image'     => 'customers/sims',
            'kk_image'      => 'customers/kk',
            'pelajar_image' => 'customers/idcards',
        ];

        foreach ($imageFields as $field => $directory) {
            if (!empty($data[$field]) && is_string($data[$field])) {
                $originalPath = $data[$field];
                $fullPath     = Storage::disk('public')->path($originalPath);

                // Hanya proses jika file ada dan bukan sudah WebP
                if (file_exists($fullPath) && !str_ends_with(strtolower($originalPath), '.webp')) {
                    try {
                        $imageContent = file_get_contents($fullPath);
                        $webpContent  = (new \ReflectionMethod($imageService, 'convertToWebpBinary'))
                            ->invoke($imageService, $imageContent);

                        // Bangun path baru dengan ekstensi .webp
                        $newPath = preg_replace('/\.[^.]+$/', '.webp', $originalPath);
                        Storage::disk('public')->put($newPath, $webpContent);

                        // Hapus file lama jika path berbeda
                        if ($newPath !== $originalPath) {
                            Storage::disk('public')->delete($originalPath);
                        }

                        $data[$field] = $newPath;
                    } catch (\Throwable $e) {
                        // Jika konversi gagal, tetap gunakan file asli (graceful degradation)
                        \Illuminate\Support\Facades\Log::warning("ImageUploadService: gagal konversi {$field} ke WebP: " . $e->getMessage());
                    }
                }
            }
        }

        return $data;
    }
}

