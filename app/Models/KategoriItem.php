<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'kategori_items';

    protected $fillable = [
        'kode',
        'nama',
    ];

    /**
     * Relasi many to many ke Master Items.
     */
    public function masterItems(): BelongsToMany
    {
        return $this->belongsToMany(
            MasterItem::class,
            'kategori_item_master_item',
            'kategori_item_id',
            'master_item_id'
        )->withTimestamps();
    }
}
