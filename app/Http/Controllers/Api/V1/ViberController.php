<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ViberController extends Controller
{
	public function handleCallback($token, Request $request)
	{
        return "OK";
	}
}
