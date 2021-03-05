<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use pcrov\JsonReader\JsonReader;
use Illuminate\Support\Facades\File;

class MigrateController extends Controller
{
    public function store()
    {
        $reader = new JsonReader();

        $reader->open(base_path('resources/opdracht/challenge.json'));
     
        while ($reader->read()) {
            // Loads!!
            var_dump($reader->value());
        };

        $reader->close();
    }
}
