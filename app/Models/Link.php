<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    // The primary key associated with the table.
    protected $primaryKey = 'resource_id';

    // Indicates if the model should be timestamped.
    public $timestamps = false;

    // The model's default values for attributes.
    protected $attributes = [
        'open_new_tab' => false,
    ];

    // The attributes that are mass assignable.
    protected $fillable = [
        'link',
        'open_new_tab',
    ];

    // Define relationship with Resource model
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
