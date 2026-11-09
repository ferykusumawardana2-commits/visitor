<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    protected $table = 'jabatan';

    protected $primaryKey = 'id_jabatan';

    public $timestamps = false;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'nama_jabatan',
    ];

    /**
     * Get the employee position assignments for this jabatan.
     */
    public function jabatanPenduduk(): HasMany
    {
        return $this->hasMany(JabatanPenduduk::class, 'id_jabatan', 'id_jabatan');
    }
}
