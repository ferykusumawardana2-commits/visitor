<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penduduk extends Model
{
    protected $table = 'penduduk';

    protected $primaryKey = 'id_penduduk';

    public $timestamps = false;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'nama_penduduk',
        'nik',
        'kk',
        'jenis_kelamin',
        'alamat',
        'username',
        'password',
        'status',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get all position assignments for this employee/resident.
     */
    public function jabatanPenduduk(): HasMany
    {
        return $this->hasMany(JabatanPenduduk::class, 'id_penduduk', 'id_penduduk');
    }
}
