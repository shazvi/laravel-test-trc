<?php

namespace Tests;

class Helper
{
    public const FORM_HEADERS = [ 'content-type' => 'multipart/form-data' ];

    public const TEST_FORM_DATA = [
        'title' => "test title",
        'type' => 1,
        'description' => "Lorem ipsum",
        'html' => "<div>Hello</div>",
    ];
}
