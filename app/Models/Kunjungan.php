<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kunjungan extends Model
{
    protected $table = 'kunjungan';

    protected $primaryKey = 'id_kunjungan';

    public $timestamps = false;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'id_settings_kunjungan',
        'id_pemohon',
        'konfrimasi_admin',
        'tanggal_waktu_admin',
        'konfrimasi_penduduk',
        'tanggal_waktu_penduduk',
        'catatan_admin',
        'catatan_penduduk',
        'tujuannya',
        'tanggal_waktu',
    ];

    /**
     * Get the schedule setting for this visit.
     */
    public function settingsKunjungan(): BelongsTo
    {
        return $this->belongsTo(SettingsKunjungan::class, 'id_settings_kunjungan', 'id_settings_kunjungan');
    }

    /**
     * Get the applicant (pemohon) who submitted this visit request.
     */
    public function pemohon(): BelongsTo
    {
        return $this->belongsTo(Pemohon::class, 'id_pemohon', 'id_pemohon');
    }
}
