<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Imagem extends Model
{
    use HasFactory, SoftDeletes;

    protected $KeyType = 'string';

    protected $fillable = [
        'uuid',
        'imovel_id',
        'name',
        'url',
    ];

    protected $dates = [
        'deleted_at',
        'created_at',
        'update_at',
    ];

    protected static function booted()
    {
        static::creating(fn(Imagem $imagem) => $imagem->id = (string) Uuid::uuid4());
    }

    public function imovel(){
        return $this->belongsTo(Imovel::class);
    }
}
