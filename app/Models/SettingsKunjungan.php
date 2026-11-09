<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SettingsKunjungan extends Model
{
    protected $table = 'settings_kunjungan';

    protected $primaryKey = 'id_settings_kunjungan';

    public $timestamps = false;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'id_jabatan_penduduk',
        'tanggal',
        'jam_aktif',
        'jam_non_aktif',
        'status',
    ];

    /**
     * Get the employee position assignment for this visit schedule.
     */
    public function jabatanPenduduk(): BelongsTo
    {
        return $this->belongsTo(JabatanPenduduk::class, 'id_jabatan_penduduk', 'id_jabatan_penduduk');
    }

    /**
     * Get all visit applications submitted for this schedule.
     */
    public function kunjungan(): HasMany
    {
        return $this->hasMany(Kunjungan::class, 'id_settings_kunjungan', 'id_settings_kunjungan');
    }
}
