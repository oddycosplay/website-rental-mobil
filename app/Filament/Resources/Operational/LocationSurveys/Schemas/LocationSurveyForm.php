<?php
namespace App\Filament\Resources\Operational\LocationSurveys\Schemas;

use App\Models\Booking;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class LocationSurveyForm
{
    public static function configure(Form $form): Form
    {
        return $form->schema([

            // ── INFORMASI DASAR ─────────────────────────────────────
            Section::make('Informasi Dasar & Verifikasi')
                ->icon('heroicon-o-information-circle')
                ->columns(2)
                ->schema([
                    Select::make('booking_id')
                        ->label('Booking Code')
                        ->relationship('booking', 'booking_code')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->columnSpan(1),

                    DatePicker::make('survey_date')
                        ->label('Tanggal Survey / Verifikasi')
                        ->required()
                        ->default(now())
                        ->columnSpan(1),

                    TextInput::make('surveyor_name')
                        ->label('Nama Surveyor / Petugas')
                        ->placeholder('Nama petugas yang melakukan verifikasi')
                        ->required()
                        ->maxLength(100)
                        ->columnSpanFull(),
                ]),

            // ── DATA ALAMAT CUSTOMER ─────────────────────────────────
            Section::make('Data Alamat Customer')
                ->icon('heroicon-o-map-pin')
                ->schema([
                    Textarea::make('address')
                        ->label('Alamat Tinggal Sekarang')
                        ->placeholder('Masukkan alamat domisili lengkap customer saat ini')
                        ->required()
                        ->rows(3)
                        ->columnSpanFull(),
                ]),

            // ── VALIDASI DATA CUSTOMER ──────────────────────────────
            Section::make('Validasi Data Customer')
                ->icon('heroicon-o-shield-check')
                ->description('Pemeriksaan detail kelayakan latar belakang domisili dan pekerjaan renter')
                ->columns(2)
                ->schema([
                    
                    // STATUS TEMPAT TINGGAL
                    Section::make('Status Tempat Tinggal')
                        ->description('Pilih status kepemilikan domisili saat ini')
                        ->columnSpan(1)
                        ->schema([
                            Toggle::make('residence_status.rumah_sendiri')
                                ->label('🏡 Rumah Sendiri')
                                ->inline(true),

                            Toggle::make('residence_status.rumah_kontrak_kos')
                                ->label('🏢 Rumah Kontrak / Kos')
                                ->inline(true),

                            Toggle::make('residence_status.bersama_orangtua')
                                ->label('👨‍👩‍👧‍👦 Bersama Orang Tua')
                                ->inline(true),
                        ]),

                    // STATUS PEKERJAAN
                    Section::make('Status Pekerjaan')
                        ->description('Verifikasi pekerjaan & kesesuaian profil perusahaan')
                        ->columnSpan(1)
                        ->schema([
                            Toggle::make('job_status.pegawai_tetap')
                                ->label('💼 Pegawai Tetap')
                                ->inline(true),

                            Toggle::make('job_status.karyawan_kontrak')
                                ->label('📄 Karyawan Kontrak')
                                ->inline(true),

                            Toggle::make('job_status.perusahaan_bekerja_sesuai')
                                ->label('✅ Perusahaan & Bidang Sesuai Dokumen')
                                ->inline(true),

                            Toggle::make('job_status.perusahaan_bekerja_tidak_sesuai')
                                ->label('❌ Perusahaan & Bidang TIDAK Sesuai')
                                ->inline(true),
                        ]),

                    // WAWANCARA LINGKUNGAN / RT / RW
                    Section::make('Wawancara Tetangga & RT/RW Setempat')
                        ->description('Hasil verifikasi sosial dari lingkungan sekitar tempat tinggal renter')
                        ->columnSpanFull()
                        ->columns(2)
                        ->schema([
                            Toggle::make('neighbor_interview.konfirmasi_domisili')
                                ->label('✅ Tetangga / Ketua RT/RW mengonfirmasi domisili renter')
                                ->inline(false),

                            Toggle::make('neighbor_interview.karakter_baik')
                                ->label('✅ Karakter renter baik, kooperatif, & tidak bermasalah')
                                ->inline(false),

                            Toggle::make('neighbor_interview.tidak_ada_tunggakan')
                                ->label('✅ Bebas dari catatan buruk sosial / masalah finansial lokal')
                                ->inline(false),
                        ]),
                ]),

            // ── DOKUMENTASI FOTO ─────────────────────────────────────
            Section::make('Dokumentasi Foto Lapangan')
                ->icon('heroicon-o-camera')
                ->schema([
                    FileUpload::make('photos')
                        ->label('Foto Verifikasi Fisik / Rumah / Tempat Kerja')
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->disk('public')
                        ->directory('surveys/locations')
                        ->imageEditor()
                        ->maxFiles(5)
                        ->helperText('Upload bukti foto domisili renter atau bukti tempat kerja jika diperlukan. Maks. 5 foto.'),
                ]),

            // ── KESIMPULAN & REKOMENDASI ──────────────────────────────
            Section::make('Rekomendasi Kelayakan Renter')
                ->icon('heroicon-o-clipboard-document-check')
                ->columns(2)
                ->schema([
                    Select::make('recommendation')
                        ->label('Kesimpulan Kelayakan')
                        ->options([
                            'layak'                => '✅ Layak (Diterima)',
                            'layak_dengan_catatan' => '⚠️ Layak Dengan Catatan',
                            'tidak_layak'          => '❌ Tidak Layak (Ditolak & Blacklist)',
                        ])
                        ->required()
                        ->columnSpan(1),

                    Select::make('status')
                        ->label('Status Laporan')
                        ->options([
                            'draft'     => 'Draft',
                            'submitted' => 'Kirim untuk Review',
                            'approved'  => 'Disetujui',
                            'rejected'  => 'Ditolak',
                        ])
                        ->default('draft')
                        ->required()
                        ->columnSpan(1),

                    Textarea::make('notes')
                        ->label('Catatan Hasil Survey & Verifikasi')
                        ->placeholder('Tuliskan detail wawancara dan argumen kelayakan renter secara lengkap...')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
