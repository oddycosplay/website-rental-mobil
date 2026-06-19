<?php

namespace App\Filament\Resources\LocationSurveys\Schemas;

use App\Models\Booking;
use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Form;

class LocationSurveyForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Section::make('Informasi Utama Survei')
                            ->columnSpan(2)
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('store_id')
                                            ->label('Cabang Toko')
                                            ->options(Store::all()->pluck('name', 'id'))
                                            ->searchable()
                                            ->required(),

                                        Forms\Components\Select::make('booking_id')
                                            ->label('Kode Pesanan / Booking')
                                            ->options(function () {
                                                return Booking::with('customer')
                                                    ->get()
                                                    ->mapWithKeys(function ($booking) {
                                                        $customerName = $booking->customer->name ?? 'Tanpa Nama';
                                                        return [$booking->id => "{$booking->booking_code} - {$customerName}"];
                                                    });
                                            })
                                            ->searchable()
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function (callable $set, $state) {
                                                $booking = Booking::find($state);
                                                if ($booking) {
                                                    $set('store_id', $booking->store_id);
                                                }
                                            }),

                                        Forms\Components\TextInput::make('surveyor_name')
                                            ->label('Nama Surveyor')
                                            ->required()
                                            ->maxLength(255),

                                        Forms\Components\DatePicker::make('survey_date')
                                            ->label('Tanggal Survei')
                                            ->default(now())
                                            ->required(),

                                        Forms\Components\Select::make('survey_type')
                                            ->label('Tipe Survei')
                                            ->options([
                                                'delivery' => 'Pengantaran Mobil (Delivery)',
                                                'pickup' => 'Pengambilan Mobil (Pickup)',
                                            ])
                                            ->required(),
                                    ]),

                                Forms\Components\Textarea::make('address')
                                    ->label('Alamat Rumah Kustomer')
                                    ->required()
                                    ->rows(3),
                            ]),

                        Forms\Components\Section::make('Status Verifikasi & Rekomendasi')
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\Select::make('recommendation')
                                    ->label('Rekomendasi Kelayakan')
                                    ->options([
                                        'layak' => 'Layak Disewakan',
                                        'tidak_layak' => 'Tidak Layak Disewakan',
                                    ])
                                    ->required(),

                                Forms\Components\Select::make('status')
                                    ->label('Status Persetujuan')
                                    ->options([
                                        'pending' => 'Pending',
                                        'approved' => 'Disetujui (Approved)',
                                        'rejected' => 'Ditolak (Rejected)',
                                    ])
                                    ->default('pending')
                                    ->required(),

                                Forms\Components\Textarea::make('notes')
                                    ->label('Catatan Surveyor / Admin')
                                    ->rows(4),
                            ]),
                    ]),

                Forms\Components\Section::make('Detail Kelayakan Lokasi (Parameter Kualitatif)')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Fieldset::make('Status Tempat Tinggal')
                                    ->schema([
                                        Forms\Components\Select::make('residence_status.status')
                                            ->label('Status Kepemilikan')
                                            ->options([
                                                'Milik Sendiri' => 'Milik Sendiri',
                                                'Kontrak/Sewa' => 'Kontrak / Sewa',
                                                'Rumah Dinas' => 'Rumah Dinas',
                                                'Rumah Orang Tua' => 'Rumah Orang Tua',
                                            ])
                                            ->required(),

                                        Forms\Components\TextInput::make('residence_status.proof')
                                            ->label('Bukti Kepemilikan (PBB/AJB/Listrik/Kontrak)')
                                            ->placeholder('Contoh: Rekening Listrik & PBB')
                                            ->required(),
                                    ])
                                    ->columnSpan(1),

                                Forms\Components\Fieldset::make('Kondisi Pekerjaan')
                                    ->schema([
                                        Forms\Components\TextInput::make('job_status.occupation')
                                            ->label('Profesi / Pekerjaan')
                                            ->placeholder('Contoh: Karyawan BUMN')
                                            ->required(),

                                        Forms\Components\TextInput::make('job_status.company')
                                            ->label('Nama Instansi / Perusahaan')
                                            ->placeholder('Contoh: PT Kereta Api Indonesia')
                                            ->required(),
                                    ])
                                    ->columnSpan(1),

                                Forms\Components\Fieldset::make('Konfirmasi Lingkungan')
                                    ->schema([
                                        Forms\Components\TextInput::make('neighbor_interview.rt_rw_verification')
                                            ->label('Verifikasi RT/RW / Tetangga')
                                            ->placeholder('Contoh: Tetangga konfirmasi tinggal 3 tahun')
                                            ->required(),

                                        Forms\Components\TextInput::make('neighbor_interview.character')
                                            ->label('Kesan Karakter Kustomer')
                                            ->placeholder('Contoh: Bersosialisasi dengan baik')
                                            ->required(),
                                    ])
                                    ->columnSpan(1),
                            ]),
                    ]),

                Forms\Components\Section::make('Dokumentasi Foto Lokasi')
                    ->schema([
                        Forms\Components\FileUpload::make('photos')
                            ->label('Upload Foto Rumah / Lokasi')
                            ->multiple()
                            ->disk('public')
                            ->directory('location-surveys/photos')
                            ->image()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
