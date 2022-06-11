<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Resource extends Model
{
    use HasFactory;

    // Lookup type ID by type name
    public const TYPE_ID = [
        'HTML' => 1,
        'Link' => 2,
        'PDF' => 3,
    ];

    // Lookup type name by type ID
    public const TYPE_NAME = [
        1 => 'HTML',
        2 => 'Link',
        3 => 'PDF',
    ];

    // The attributes that are mass assignable.
    protected $fillable = [
        'title',
        'type',
    ];

    // Define relationship with sub-resources
    public function html()
    {
        return $this->hasOne(Html::class);
    }

    public function link()
    {
        return $this->hasOne(Link::class);
    }

    public function pdf()
    {
        return $this->hasOne(Pdf::class);
    }

    /**
     * Query resources from db with related sub-resources.
     * Query builder is used instead of Eloquent methods because Eloquent uses multiple queries.
     *
     * @return Collection
     */
    public static function getAll(): Collection
    {
        return DB::table('resources')
            ->select(
                'resources.id', 'resources.type', 'resources.title', 'resources.created_at', 'resources.updated_at',
                'htmls.description', 'htmls.html', 'links.link', 'links.open_new_tab', 'pdfs.filename'
            )
            ->leftJoin('htmls', function ($join)
            {
                $join->on('resources.id', '=', 'htmls.resource_id')
                    ->where('resources.type', '=', self::TYPE_ID['HTML']);
            })
            ->leftJoin('links', function ($join)
            {
                $join->on('resources.id', '=', 'links.resource_id')
                    ->where('resources.type', '=', self::TYPE_ID['Link']);
            })
            ->leftJoin('pdfs', function ($join)
            {
                $join->on('resources.id', '=', 'pdfs.resource_id')
                    ->where('resources.type', '=', self::TYPE_ID['PDF']);
            })
            ->get();
    }
}
