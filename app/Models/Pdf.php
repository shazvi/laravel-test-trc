<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pdf extends Model
{
    use HasFactory;

    // The primary key associated with the table.
    protected $primaryKey = 'resource_id';

    // Indicates if the model should be timestamped.
    public $timestamps = false;

    // The attributes that are mass assignable.
    protected $fillable = [
        'filename',
    ];

    // Define relationship with Resource model
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
