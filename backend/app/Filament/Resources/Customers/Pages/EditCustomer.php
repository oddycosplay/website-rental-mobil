<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use App\Models\User;
use App\Services\ImageUploadService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Konversi semua gambar yang diupload (baru) ke format WebP sebelum data disimpan.
     * Jika field berisi path yang sudah ada (tidak diubah), lewati konversi.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $imageService   = app(ImageUploadService::class);
        $existingRecord = $this->record;

        $imageFields = [
            'selfie_image'  => 'customers/selfies',
            'ktp_image'     => 'customers/identities',
            'sim_image'     => 'customers/sims',
            'kk_image'      => 'customers/kk',
            'pelajar_image' => 'customers/idcards',
        ];

        foreach ($imageFields as $field => $directory) {
            if (!empty($data[$field]) && is_string($data[$field])) {
                $originalPath   = $data[$field];
                $existingValue  = $existingRecord->{$field} ?? null;

                // Lewati jika path sama dengan yang sudah ada di database (tidak diubah)
                if ($originalPath === $existingValue) {
                    continue;
                }

                $fullPath = Storage::disk('public')->path($originalPath);

                // Hanya proses jika file ada dan bukan sudah WebP
                if (file_exists($fullPath) && !str_ends_with(strtolower($originalPath), '.webp')) {
                    try {
                        $imageContent = file_get_contents($fullPath);
                        $webpContent  = (new \ReflectionMethod($imageService, 'convertToWebpBinary'))
                            ->invoke($imageService, $imageContent);

                        $newPath = preg_replace('/\.[^.]+$/', '.webp', $originalPath);
                        Storage::disk('public')->put($newPath, $webpContent);

                        if ($newPath !== $originalPath) {
                            Storage::disk('public')->delete($originalPath);
                        }

                        // Hapus file lama dari database jika diganti
                        if ($existingValue && $existingValue !== $newPath) {
                            $imageService->deleteOldFile($existingValue);
                        }

                        $data[$field] = $newPath;
                    } catch (\Throwable $e) {
                        Log::warning("ImageUploadService: gagal konversi {$field} ke WebP: " . $e->getMessage());
                    }
                }
            }
        }

        return $data;
    }
}

