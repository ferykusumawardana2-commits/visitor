<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use App\Models\JabatanPenduduk;
use App\Models\Kunjungan;
use App\Models\Pemohon;
use App\Models\Penduduk;
use App\Models\SettingsKegiatan;
use App\Models\SettingsKunjungan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the Si Kunjung Pemda Visitor application's database.
     */
    public function run(): void
    {
        // 1. Seed Jabatan Si Kunjung Pemda
        $jabatanDirektur = Jabatan::firstOrCreate(['nama_jabatan' => 'Direktur Utama']);
        $jabatanOperasional = Jabatan::firstOrCreate(['nama_jabatan' => 'Manajer Operasional']);
        $jabatanSdm = Jabatan::firstOrCreate(['nama_jabatan' => 'Kepala Divisi SDM & Umum']);
        $jabatanIt = Jabatan::firstOrCreate(['nama_jabatan' => 'Kepala Divisi IT & Digital']);
        $jabatanAdmin = Jabatan::firstOrCreate(['nama_jabatan' => 'Administrator Resepsionis']);

        // 2. Seed Penduduk (Admin & Karyawan Si Kunjung Pemda)
        $admin = Penduduk::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama_penduduk' => 'Ferry Kusuma Wardana',
                'nik' => '3201011001900001',
                'kk' => '3201011001900000',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Jenderal Sudirman Kav. 45, Jakarta',
                'password' => Hash::make('admin123'),
                'status' => 'Admin',
            ]
        );

        $karyawan1 = Penduduk::firstOrCreate(
            ['username' => 'budi'],
            [
                'nama_penduduk' => 'Ir. Budi Santoso, M.M.',
                'nik' => '3201011505850002',
                'kk' => '3201011505850000',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Gatot Subroto No. 12, Jakarta Selatan',
                'password' => Hash::make('karyawan123'),
                'status' => 'Penduduk',
            ]
        );

        $karyawan2 = Penduduk::firstOrCreate(
            ['username' => 'siti'],
            [
                'nama_penduduk' => 'Siti Rahmawati, S.E.',
                'nik' => '3201012008920003',
                'kk' => '3201012008920000',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Rasuna Said Blok X-5, Kuningan',
                'password' => Hash::make('karyawan123'),
                'status' => 'Penduduk',
            ]
        );

        $karyawan3 = Penduduk::firstOrCreate(
            ['username' => 'andi'],
            [
                'nama_penduduk' => 'Andi Pratama, S.Psi.',
                'nik' => '3201011203910004',
                'kk' => '3201011203910000',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. TB Simatupang No. 88, Jakarta Selatan',
                'password' => Hash::make('karyawan123'),
                'status' => 'Penduduk',
            ]
        );

        $karyawan4 = Penduduk::firstOrCreate(
            ['username' => 'dewi'],
            [
                'nama_penduduk' => 'Dewi Lestari, S.Kom.',
                'nik' => '3201012511940005',
                'kk' => '3201012511940000',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Kemang Raya No. 21, Jakarta Selatan',
                'password' => Hash::make('karyawan123'),
                'status' => 'Penduduk',
            ]
        );

        // 3. Seed Jabatan Penduduk (Struktur Karyawan)
        $jpAdmin = JabatanPenduduk::resolvePair($admin->id_penduduk, $jabatanAdmin->id_jabatan);
        $jpDirektur = JabatanPenduduk::resolvePair($karyawan1->id_penduduk, $jabatanDirektur->id_jabatan);
        $jpOperasional = JabatanPenduduk::resolvePair($karyawan2->id_penduduk, $jabatanOperasional->id_jabatan);
        $jpSdm = JabatanPenduduk::resolvePair($karyawan3->id_penduduk, $jabatanSdm->id_jabatan);
        $jpIt = JabatanPenduduk::resolvePair($karyawan4->id_penduduk, $jabatanIt->id_jabatan);

        // 4. Seed Pemohon
        $pemohon1 = Pemohon::firstOrCreate(
            ['email' => 'pemohon@example.com'],
            [
                'nama' => 'Rizky Pratama',
                'telepon' => '081234567890',
                'password' => Hash::make('pemohon123'),
            ]
        );

        $pemohon2 = Pemohon::firstOrCreate(
            ['email' => 'nadia@example.com'],
            [
                'nama' => 'Nadia Putri Maharani',
                'telepon' => '081298765432',
                'password' => Hash::make('pemohon123'),
            ]
        );

        // 5. Seed Settings Kunjungan (Jadwal Kunjungan)
        $today = Carbon::today();

        $jadwal1 = SettingsKunjungan::firstOrCreate(
            [
                'id_jabatan_penduduk' => $jpDirektur->id_jabatan_penduduk,
                'tanggal' => $today->copy()->addDays(1)->toDateString(),
            ],
            [
                'jam_aktif' => '09:00:00',
                'jam_non_aktif' => '12:00:00',
                'status' => 'Y',
            ]
        );

        $jadwal2 = SettingsKunjungan::firstOrCreate(
            [
                'id_jabatan_penduduk' => $jpOperasional->id_jabatan_penduduk,
                'tanggal' => $today->copy()->addDays(1)->toDateString(),
            ],
            [
                'jam_aktif' => '08:30:00',
                'jam_non_aktif' => '15:30:00',
                'status' => 'Y',
            ]
        );

        $jadwal3 = SettingsKunjungan::firstOrCreate(
            [
                'id_jabatan_penduduk' => $jpSdm->id_jabatan_penduduk,
                'tanggal' => $today->copy()->addDays(2)->toDateString(),
            ],
            [
                'jam_aktif' => '10:00:00',
                'jam_non_aktif' => '16:00:00',
                'status' => 'Y',
            ]
        );

        $jadwal4 = SettingsKunjungan::firstOrCreate(
            [
                'id_jabatan_penduduk' => $jpIt->id_jabatan_penduduk,
                'tanggal' => $today->copy()->addDays(3)->toDateString(),
            ],
            [
                'jam_aktif' => '13:00:00',
                'jam_non_aktif' => '17:00:00',
                'status' => 'T',
            ]
        );

        // 6. Seed Settings Kegiatan (Agenda / Kegiatan Karyawan)
        SettingsKegiatan::firstOrCreate(
            [
                'id_jabatan_penduduk' => $jpDirektur->id_jabatan_penduduk,
                'kegiatan' => 'Rapat Evaluasi Triwulan Direksi Si Kunjung Pemda & Mitra Strategis',
            ],
            [
                'tanggal_awal' => $today->toDateString(),
                'tanggal_akhir' => $today->copy()->addDays(2)->toDateString(),
            ]
        );

        SettingsKegiatan::firstOrCreate(
            [
                'id_jabatan_penduduk' => $jpOperasional->id_jabatan_penduduk,
                'kegiatan' => 'Inspeksi Standar Operasional & Audit Layanan Kunjungan Tamu',
            ],
            [
                'tanggal_awal' => $today->copy()->addDays(1)->toDateString(),
                'tanggal_akhir' => $today->copy()->addDays(3)->toDateString(),
            ]
        );

        SettingsKegiatan::firstOrCreate(
            [
                'id_jabatan_penduduk' => $jpIt->id_jabatan_penduduk,
                'kegiatan' => 'Pemeliharaan Infrastruktur Server & Integrasi Sistem Visitor Si Kunjung Pemda',
            ],
            [
                'tanggal_awal' => $today->copy()->addDays(2)->toDateString(),
                'tanggal_akhir' => $today->copy()->addDays(4)->toDateString(),
            ]
        );

        // 7. Seed Kunjungan (Data Pengajuan & Histori Kunjungan)
        if (Kunjungan::count() === 0) {
            Kunjungan::create([
                'id_settings_kunjungan' => $jadwal1->id_settings_kunjungan,
                'id_pemohon' => $pemohon1->id_pemohon,
                'konfrimasi_admin' => 'Y',
                'tanggal_waktu_admin' => Carbon::now()->subHours(5)->format('Y-m-d H:i:s'),
                'konfrimasi_penduduk' => 'Y',
                'tanggal_waktu_penduduk' => Carbon::now()->subHours(3)->format('Y-m-d H:i:s'),
                'catatan_admin' => 'Dokumen permohonan lengkap, silakan menuju Lobby Utama.',
                'catatan_penduduk' => 'Siap menerima kunjungan di Ruang Rapat Lt. 3.',
                'tujuannya' => 'Presentasi Kerja Sama Pengadaan Sistem Otomasi Industri Si Kunjung Pemda',
                'tanggal_waktu' => $today->copy()->addDays(1)->setTime(9, 30)->format('Y-m-d H:i:s'),
            ]);

            Kunjungan::create([
                'id_settings_kunjungan' => $jadwal2->id_settings_kunjungan,
                'id_pemohon' => $pemohon1->id_pemohon,
                'konfrimasi_admin' => 'T',
                'tanggal_waktu_admin' => Carbon::now()->subHours(2)->format('Y-m-d H:i:s'),
                'konfrimasi_penduduk' => null,
                'tanggal_waktu_penduduk' => null,
                'catatan_admin' => 'Jadwal bertabrakan dengan agenda audit eksternal, mohon pilih tanggal berikutnya.',
                'catatan_penduduk' => null,
                'tujuannya' => 'Konsultasi Teknis Operasional Gudang Logistik',
                'tanggal_waktu' => $today->copy()->addDays(1)->setTime(14, 0)->format('Y-m-d H:i:s'),
            ]);

            Kunjungan::create([
                'id_settings_kunjungan' => $jadwal3->id_settings_kunjungan,
                'id_pemohon' => $pemohon2->id_pemohon,
                'konfrimasi_admin' => 'Y',
                'tanggal_waktu_admin' => Carbon::now()->subHour()->format('Y-m-d H:i:s'),
                'konfrimasi_penduduk' => null,
                'tanggal_waktu_penduduk' => null,
                'catatan_admin' => 'Telah diverifikasi oleh resepsionis.',
                'catatan_penduduk' => null,
                'tujuannya' => 'Wawancara Kemitraan Program Magang Industri SDM Si Kunjung Pemda',
                'tanggal_waktu' => $today->copy()->addDays(2)->setTime(10, 30)->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
