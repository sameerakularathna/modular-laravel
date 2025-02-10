<?php
namespace Custom\GoldRate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoldRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'metal',
        'with_duty_free',
        'duty_free',
        'timestamp',
        'created_by'
    ];
}
