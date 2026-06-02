<?php

namespace App\Filament\Resources\Operational\VehicleInspections\Schemas;

use App\Models\Booking;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class VehicleInspectionForm
{
    public static function configure(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Pengecekan Kendaraan')
                ->tabs([

                    // ── TAB 1: INFORMASI UMUM ─────────────────────────────
                    Tab::make('📋 Info Umum')
                        ->schema([
                            Section::make()->columns(2)->schema([
                                Select::make('booking_id')
                                    ->label('Booking')
                                    ->relationship('booking', 'booking_code')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (?string $state, ?string $old, Set $set) {
                                        if ($state) {
                                            $booking = Booking::query()->find((int) $state);
                                            if ($booking) {
                                                $set('car_id', $booking->car_id);
                                            }
                                        }
                                    })
                                    ->columnSpan(1),

                                Select::make('car_id')
                                    ->label('Kendaraan')
                                    ->relationship('car', 'car_name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(1),

                                Select::make('inspection_type')
                                    ->label('Tipe Pengecekan')
                                    ->options([
                                        'pre_rental'  => '🚗 Pengecekan Keluar (Pre-Rental)',
                                        'post_rental' => '🔍 Pengecekan Masuk (Post-Rental)',
                                    ])
                                    ->required()
                                    ->live()
                                    ->columnSpan(1),

                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'draft'     => 'Draft',
                                        'submitted' => 'Kirim untuk Review',
                                        'approved'  => 'Disetujui',
                                    ])
                                    ->default('draft')
                                    ->required()
                                    ->columnSpan(1),

                                DateTimePicker::make('inspected_at')
                                    ->label('Tanggal & Jam Pengecekan')
                                    ->required()
                                    ->default(now())
                                    ->columnSpan(1),

                                TextInput::make('inspector_name')
                                    ->label('Nama Petugas Pemeriksa')
                                    ->placeholder('Nama lengkap petugas')
                                    ->required()
                                    ->maxLength(100)
                                    ->columnSpan(1),

                                TextInput::make('odometer_km')
                                    ->label('Odometer (KM)')
                                    ->numeric()
                                    ->suffix('km')
                                    ->placeholder('0')
                                    ->columnSpan(1),

                                Select::make('fuel_level')
                                    ->label('Level Bahan Bakar')
                                    ->options([
                                        'full'          => '⛽ Full (Penuh)',
                                        'three_quarter' => '⛽ ¾ (Tiga Perempat)',
                                        'half'          => '⛽ ½ (Setengah)',
                                        'quarter'       => '⛽ ¼ (Seperempat)',
                                        'empty'         => '⛽ Hampir Kosong',
                                    ])
                                    ->default('full')
                                    ->required()
                                    ->columnSpan(1),
                            ]),
                        ]),

                    // ── TAB 2: EKSTERIOR ──────────────────────────────────
                    Tab::make('🚗 Eksterior')
                        ->schema([
                            Section::make('Panel Bodi')
                                ->columns(2)
                                ->schema([
                                    Select::make('exterior.body_depan')
                                        ->label('Body Depan')
                                        ->options(self::bodyOptions())
                                        ->default('baik')->required(),
                                    Select::make('exterior.body_kiri')
                                        ->label('Body Kiri')
                                        ->options(self::bodyOptions())
                                        ->default('baik')->required(),
                                    Select::make('exterior.body_kanan')
                                        ->label('Body Kanan')
                                        ->options(self::bodyOptions())
                                        ->default('baik')->required(),
                                    Select::make('exterior.body_belakang')
                                        ->label('Body Belakang')
                                        ->options(self::bodyOptions())
                                        ->default('baik')->required(),
                                ]),

                            Section::make('Kaca & Spion')
                                ->columns(2)
                                ->schema([
                                    Select::make('exterior.kaca_depan')
                                        ->label('Kaca Depan (Windshield)')
                                        ->options(self::glassOptions())
                                        ->default('baik')->required(),
                                    Select::make('exterior.kaca_belakang')
                                        ->label('Kaca Belakang')
                                        ->options(self::glassOptions())
                                        ->default('baik')->required(),
                                    Select::make('exterior.spion_kiri')
                                        ->label('Spion Kiri')
                                        ->options(self::mirrorOptions())
                                        ->default('baik')->required(),
                                    Select::make('exterior.spion_kanan')
                                        ->label('Spion Kanan')
                                        ->options(self::mirrorOptions())
                                        ->default('baik')->required(),
                                ]),

                            Section::make('Lampu')
                                ->columns(2)
                                ->schema([
                                    Select::make('exterior.lampu_depan_kiri')
                                        ->label('Lampu Depan Kiri')
                                        ->options(self::lightOptions())
                                        ->default('baik')->required(),
                                    Select::make('exterior.lampu_depan_kanan')
                                        ->label('Lampu Depan Kanan')
                                        ->options(self::lightOptions())
                                        ->default('baik')->required(),
                                    Select::make('exterior.lampu_belakang_kiri')
                                        ->label('Lampu Belakang Kiri')
                                        ->options(self::lightOptions())
                                        ->default('baik')->required(),
                                    Select::make('exterior.lampu_belakang_kanan')
                                        ->label('Lampu Belakang Kanan')
                                        ->options(self::lightOptions())
                                        ->default('baik')->required(),
                                ]),

                            Section::make('Ban')
                                ->columns(2)
                                ->schema([
                                    Select::make('exterior.ban_depan_kiri')
                                        ->label('Ban Depan Kiri')
                                        ->options(self::tireOptions())
                                        ->default('baik')->required(),
                                    Select::make('exterior.ban_depan_kanan')
                                        ->label('Ban Depan Kanan')
                                        ->options(self::tireOptions())
                                        ->default('baik')->required(),
                                    Select::make('exterior.ban_belakang_kiri')
                                        ->label('Ban Belakang Kiri')
                                        ->options(self::tireOptions())
                                        ->default('baik')->required(),
                                    Select::make('exterior.ban_belakang_kanan')
                                        ->label('Ban Belakang Kanan')
                                        ->options(self::tireOptions())
                                        ->default('baik')->required(),
                                    Select::make('exterior.ban_serep')
                                        ->label('Ban Serep')
                                        ->options(['ada' => '✅ Ada', 'tidak_ada' => '❌ Tidak Ada'])
                                        ->default('ada')->required(),
                                ]),
                        ]),

                    // ── TAB 3: INTERIOR ───────────────────────────────────
                    Tab::make('🪑 Interior')
                        ->schema([
                            Section::make('Kebersihan & Kenyamanan')
                                ->columns(2)
                                ->schema([
                                    Select::make('interior.kebersihan_kabin')
                                        ->label('Kebersihan Kabin')
                                        ->options([
                                            'bersih'      => '✅ Bersih',
                                            'kotor'       => '⚠️ Kotor',
                                            'sangat_kotor'=> '❌ Sangat Kotor',
                                        ])
                                        ->default('bersih')->required(),
                                    Select::make('interior.karpet')
                                        ->label('Karpet')
                                        ->options(self::kondisiOptions())
                                        ->default('baik')->required(),
                                ]),

                            Section::make('Kursi & Sabuk')
                                ->columns(2)
                                ->schema([
                                    Select::make('interior.jok_depan_kiri')
                                        ->label('Jok Depan Kiri')
                                        ->options(self::seatOptions())
                                        ->default('baik')->required(),
                                    Select::make('interior.jok_depan_kanan')
                                        ->label('Jok Depan Kanan')
                                        ->options(self::seatOptions())
                                        ->default('baik')->required(),
                                    Select::make('interior.jok_belakang')
                                        ->label('Jok Belakang')
                                        ->options(self::seatOptions())
                                        ->default('baik')->required(),
                                    Select::make('interior.sabuk_pengaman')
                                        ->label('Sabuk Pengaman')
                                        ->options(self::functionOptions())
                                        ->default('berfungsi')->required(),
                                ]),

                            Section::make('Struktur & Fitur')
                                ->columns(2)
                                ->schema([
                                    Select::make('interior.plafon')
                                        ->label('Plafon')
                                        ->options(self::kondisiOptions())
                                        ->default('baik')->required(),
                                    Select::make('interior.dashboard')
                                        ->label('Dashboard')
                                        ->options([
                                            'baik'  => '✅ Baik',
                                            'rusak' => '❌ Rusak',
                                        ])
                                        ->default('baik')->required(),
                                    Select::make('interior.setir')
                                        ->label('Setir (Kemudi)')
                                        ->options([
                                            'baik'  => '✅ Baik',
                                            'rusak' => '❌ Rusak',
                                        ])
                                        ->default('baik')->required(),
                                    Select::make('interior.ac')
                                        ->label('AC')
                                        ->options([
                                            'baik'           => '✅ Dingin & Berfungsi Baik',
                                            'kurang_dingin'  => '⚠️ Kurang Dingin',
                                            'tidak_berfungsi'=> '❌ Tidak Berfungsi',
                                        ])
                                        ->default('baik')->required(),
                                    Select::make('interior.audio')
                                        ->label('Audio / Radio')
                                        ->options(self::functionOptions())
                                        ->default('berfungsi')->required(),
                                    Select::make('interior.power_window')
                                        ->label('Power Window')
                                        ->options(self::functionOptions())
                                        ->default('berfungsi')->required(),
                                ]),
                        ]),

                    // ── TAB 4: KELENGKAPAN ────────────────────────────────
                    Tab::make('🧰 Kelengkapan')
                        ->schema([
                            Section::make('Dokumen Kendaraan')
                                ->columns(2)
                                ->schema([
                                    Toggle::make('equipment.stnk')
                                        ->label('STNK')
                                        ->default(true)->inline(false),
                                    Toggle::make('equipment.kunci_utama')
                                        ->label('Kunci Utama')
                                        ->default(true)->inline(false),
                                ]),

                            Section::make('Perlengkapan Darurat')
                                ->columns(2)
                                ->schema([
                                    Toggle::make('equipment.dongkrak')
                                        ->label('Dongkrak')
                                        ->default(true)->inline(false),
                                    Toggle::make('equipment.kunci_roda')
                                        ->label('Kunci Roda (Ban Serep)')
                                        ->default(true)->inline(false),
                                    Toggle::make('equipment.segitiga_pengaman')
                                        ->label('Segitiga Pengaman')
                                        ->default(true)->inline(false),
                                    Toggle::make('equipment.p3k')
                                        ->label('Kotak P3K')
                                        ->default(true)->inline(false),
                                    Toggle::make('equipment.payung')
                                        ->label('Payung')
                                        ->default(false)->inline(false),
                                ]),

                            Section::make('E-Toll')
                                ->columns(2)
                                ->schema([
                                    Toggle::make('equipment.kartu_etoll')
                                        ->label('Kartu E-Toll')
                                        ->default(true)
                                        ->live()
                                        ->inline(false),
                                    TextInput::make('equipment.saldo_etoll')
                                        ->label('Saldo E-Toll (Rp)')
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->placeholder('0')
                                        ->visible(fn (Get $get) => $get('equipment.kartu_etoll'))
                                        ->columnSpan(1),
                                ]),
                        ]),

                    // ── TAB 5: MESIN & BBM ────────────────────────────────
                    Tab::make('⚙️ Mesin & BBM')
                        ->schema([
                            Section::make('Kondisi Cairan')
                                ->columns(2)
                                ->schema([
                                    Select::make('engine.kondisi_oli')
                                        ->label('Kondisi Oli Mesin')
                                        ->options([
                                            'normal'       => '✅ Normal',
                                            'perlu_diganti'=> '⚠️ Perlu Diganti',
                                        ])
                                        ->default('normal')->required(),
                                    Select::make('engine.air_radiator')
                                        ->label('Air Radiator')
                                        ->options([
                                            'normal'         => '✅ Normal',
                                            'perlu_ditambah' => '⚠️ Perlu Ditambah',
                                        ])
                                        ->default('normal')->required(),
                                    Select::make('engine.air_wiper')
                                        ->label('Air Wiper')
                                        ->options([
                                            'normal'         => '✅ Normal',
                                            'perlu_ditambah' => '⚠️ Perlu Ditambah',
                                        ])
                                        ->default('normal')->required(),
                                ]),

                            Section::make('Kondisi Mesin')
                                ->columns(2)
                                ->schema([
                                    Select::make('engine.kondisi_mesin')
                                        ->label('Kondisi Mesin')
                                        ->options([
                                            'normal'      => '✅ Normal, Berjalan Baik',
                                            'ada_masalah' => '⚠️ Ada Masalah / Bunyi Tidak Normal',
                                        ])
                                        ->default('normal')
                                        ->live()
                                        ->required(),
                                    Textarea::make('engine.catatan_mesin')
                                        ->label('Catatan Kondisi Mesin')
                                        ->placeholder('Jelaskan masalah yang ditemukan...')
                                        ->rows(3)
                                        ->visible(fn (Get $get) => $get('engine.kondisi_mesin') === 'ada_masalah')
                                        ->columnSpanFull(),
                                ]),

                            Section::make('⚠️ Denda Operasional (Post-Rental)')
                                ->icon('heroicon-o-currency-dollar')
                                ->description('Denda dihitung sebagai transaksi terpisah dan tidak ditambahkan ke total biaya sewa utama.')
                                ->visible(fn (Get $get) => $get('inspection_type') === 'post_rental')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('dirty_fine')
                                        ->label('Denda Mobil Kotor / Cuci Mobil (Rp)')
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->placeholder('0'),

                                    TextInput::make('fuel_fine')
                                        ->label('Denda Bensin Tidak Sesuai (Rp)')
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->placeholder('0'),
                                ]),
                        ]),

                    // ── TAB 6: FOTO DOKUMENTASI ───────────────────────────
                    Tab::make('📸 Foto Dokumentasi')
                        ->schema([
                            Section::make('Foto Wajib Keadaan Mobil')
                                ->columns(2)
                                ->schema([
                                    FileUpload::make('photos.depan')
                                        ->label('Foto Tampak Depan *')
                                        ->image()->disk('public')
                                        ->directory('inspections/photos')
                                        ->imageEditor()->columnSpan(1),
                                    FileUpload::make('photos.belakang')
                                        ->label('Foto Tampak Belakang *')
                                        ->image()->disk('public')
                                        ->directory('inspections/photos')
                                        ->imageEditor()->columnSpan(1),
                                    FileUpload::make('photos.kiri')
                                        ->label('Foto Tampak Kiri *')
                                        ->image()->disk('public')
                                        ->directory('inspections/photos')
                                        ->imageEditor()->columnSpan(1),
                                    FileUpload::make('photos.kanan')
                                        ->label('Foto Tampak Kanan *')
                                        ->image()->disk('public')
                                        ->directory('inspections/photos')
                                        ->imageEditor()->columnSpan(1),
                                    FileUpload::make('photos.odometer')
                                        ->label('Foto Odometer *')
                                        ->image()->disk('public')
                                        ->directory('inspections/photos')
                                        ->imageEditor()->columnSpanFull(),
                                    FileUpload::make('fuel_photos')
                                        ->label('Foto Bensin Mobil *')
                                        ->image()
                                        ->multiple()
                                        ->disk('public')
                                        ->directory('inspections/fuel')
                                        ->imageEditor()
                                        ->maxFiles(3)
                                        ->helperText('Upload bukti foto indikator bensin mobil. Maks. 3 foto.')
                                        ->columnSpanFull(),
                                ]),

                            // ── SECTION POST-RENTAL: KERUSAKAN / KONDISI KHUSUS ──
                            Section::make('⚠️ Laporan Kerusakan & Kondisi Khusus')
                                ->icon('heroicon-o-exclamation-triangle')
                                ->description('Isi bagian ini untuk Pengecekan jika ditemukan kerusakan baru atau mobil dalam keadaan kotor.')
                                ->schema([
                                    Toggle::make('damage_found')
                                        ->label('Ada Kerusakan yang Ditemukan?')
                                        ->live()
                                        ->inline(false),
                                    Textarea::make('damage_description')
                                        ->label('Deskripsi Kerusakan')
                                        ->placeholder('Jelaskan kerusakan yang ditemukan secara detail...')
                                        ->rows(4)
                                        ->visible(fn (Get $get) => $get('damage_found'))
                                        ->required(fn (Get $get) => $get('damage_found'))
                                        ->columnSpanFull(),
                                    TextInput::make('damage_cost')
                                        ->label('Estimasi Biaya Perbaikan (Rp)')
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->visible(fn (Get $get) => $get('damage_found'))
                                        ->required(fn (Get $get) => $get('damage_found')),
                                    FileUpload::make('damage_photos')
                                        ->label('Foto Kondisi Khusus [upload multiple, jika ada kerusakan / mobil dalam keadaan kotor]')
                                        ->image()
                                        ->multiple()
                                        ->disk('public')
                                        ->directory('inspections/special')
                                        ->imageEditor()
                                        ->maxFiles(10)
                                        ->columnSpanFull(),
                                ]),

                            // ── KONFIRMASI & CATATAN ─────────────────────
                            Section::make('Konfirmasi & Catatan')
                                ->columns(2)
                                ->schema([
                                    Toggle::make('customer_confirmed')
                                        ->label('Pelanggan Menyetujui Kondisi Kendaraan')
                                        ->helperText('Centang jika pelanggan sudah memeriksa dan setuju kondisi di atas.')
                                        ->inline(false)
                                        ->columnSpan(1),
                                    Textarea::make('customer_note')
                                        ->label('Catatan dari Pelanggan')
                                        ->placeholder('Catatan keberatan atau tambahan dari pelanggan...')
                                        ->rows(3)
                                        ->columnSpan(1),
                                    Textarea::make('notes')
                                        ->label('Catatan Internal Petugas')
                                        ->placeholder('Catatan tambahan untuk tim internal...')
                                        ->rows(3)
                                        ->columnSpanFull(),
                                ]),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }

    // ── Option Helpers ────────────────────────────────────────────────────────

    private static function bodyOptions(): array
    {
        return [
            'baik'  => '✅ Baik',
            'penyok'=> '⚠️ Penyok',
            'lecet' => '⚠️ Lecet / Goresan',
            'retak' => '❌ Retak / Pecah',
        ];
    }

    private static function glassOptions(): array
    {
        return [
            'baik'  => '✅ Baik',
            'baret' => '⚠️ Baret / Goresan',
            'retak' => '❌ Retak / Pecah',
        ];
    }

    private static function mirrorOptions(): array
    {
        return [
            'baik'  => '✅ Baik',
            'rusak' => '⚠️ Rusak',
            'hilang'=> '❌ Hilang',
        ];
    }

    private static function lightOptions(): array
    {
        return [
            'baik'  => '✅ Baik',
            'retak' => '⚠️ Cover Retak',
            'mati'  => '❌ Mati / Tidak Berfungsi',
        ];
    }

    private static function tireOptions(): array
    {
        return [
            'baik'  => '✅ Baik',
            'kempes'=> '⚠️ Kempes / Kurang Angin',
            'gundul'=> '⚠️ Gundul (Perlu Ganti)',
            'retak' => '❌ Retak / Bocor',
        ];
    }

    private static function kondisiOptions(): array
    {
        return [
            'baik'  => '✅ Baik',
            'kotor' => '⚠️ Kotor',
            'rusak' => '❌ Rusak',
        ];
    }

    private static function seatOptions(): array
    {
        return [
            'baik'  => '✅ Baik',
            'kotor' => '⚠️ Kotor / Noda',
            'robek' => '❌ Robek / Rusak',
        ];
    }

    private static function functionOptions(): array
    {
        return [
            'berfungsi'       => '✅ Berfungsi Normal',
            'tidak_berfungsi' => '❌ Tidak Berfungsi',
        ];
    }
}
