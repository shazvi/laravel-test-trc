<?php

namespace Database\Seeders;

use App\Models\Html;
use App\Models\Link;
use App\Models\Pdf;
use App\Models\Resource;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $htmlResource = new Resource([
            'id' => 1000,
            'title' => 'new test html title',
            'type' => 1
        ]);
        $htmlResource->save();
        $htmlResource->html()->save(new Html([
            'description' => 'test html description',
            'html' => 'test html code',
        ]));

        $linkResource = new Resource([
            'id' => 1001,
            'title' => 'new test link title',
            'type' => 2
        ]);
        $linkResource->save();
        $linkResource->link()->save(new Link([
            'link' => 'https://google.com',
            'open_new_tab' => true,
        ]));

        $pdfResource = new Resource([
            'id' => 1002,
            'title' => 'new test pdf title',
            'type' => 3
        ]);
        $pdfResource->save();
        $pdfResource->pdf()->save(new Pdf([
            'filename' => 'testfile.pdf'
        ]));
    }
}
