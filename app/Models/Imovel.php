<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Imovel extends Model
{
    use HasFactory, SoftDeletes;

    protected $KeyType = 'string';

    protected $fillable = [
        'uuid',
        'name',
        'address',
        'description',
        'value',
    ];

    protected $dates = [
        'deleted_at',
        'created_at',
        'update_at',
    ];

    protected static function booted()
    {
        static::creating(fn(Imovel $imovel) => $imovel->id = (string) Uuid::uuid4());
    }
}
