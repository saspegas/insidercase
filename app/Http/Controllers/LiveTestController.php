<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class LiveTestController extends Controller
{
    public function __invoke()
    {
        $count = 10;

        Message::factory()->count($count)->create();        

        return response()->json(['message' => "Live test ({$count}) messages created successfully"], 201);
    }
}
