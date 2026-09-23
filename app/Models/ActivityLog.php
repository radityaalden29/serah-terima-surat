<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'aksi',
        'no_agenda',
        'keterangan',
    ];

    public const AKSI_TAMBAH  = 'Ditambahkan';
    public const AKSI_UBAH    = 'Diperbarui';
    public const AKSI_HAPUS   = 'Dihapus';
    public const AKSI_IMPOR   = 'Diimpor';
}
