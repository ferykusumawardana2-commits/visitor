<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JabatanPenduduk extends Model
{
    protected $table = 'jabatan_penduduk';

    protected $primaryKey = 'id_jabatan_penduduk';

    public $timestamps = false;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'id_jabatan',
        'id_penduduk',
        'status',
    ];

    /**
     * Get the jabatan associated with this assignment.
     */
    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id_jabatan');
    }

    /**
     * Get the penduduk (employee) associated with this assignment.
     */
    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'id_penduduk', 'id_penduduk');
    }

    /**
     * Get the visit schedules for this employee position.
     */
    public function settingsKunjungan(): HasMany
    {
        return $this->hasMany(SettingsKunjungan::class, 'id_jabatan_penduduk', 'id_jabatan_penduduk');
    }

    /**
     * Get the activities for this employee position.
     */
    public function settingsKegiatan(): HasMany
    {
        return $this->hasMany(SettingsKegiatan::class, 'id_jabatan_penduduk', 'id_jabatan_penduduk');
    }

    /**
     * Resolve or create a JabatanPenduduk record for the given penduduk and jabatan.
     */
    public static function resolvePair(int $idPenduduk, int $idJabatan): self
    {
        $existing = static::where('id_penduduk', $idPenduduk)
            ->where('id_jabatan', $idJabatan)
            ->first();

        if ($existing !== null) {
            return $existing;
        }

        return static::create([
            'id_penduduk' => $idPenduduk,
            'id_jabatan' => $idJabatan,
            'status' => 'Y',
        ]);
    }
}
