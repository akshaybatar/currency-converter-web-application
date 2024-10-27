<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CurrencyHistory;
use Illuminate\Support\Facades\Cache;

class SearchHistoryController extends Controller
{
    public function index()
    {
        $histories = Cache::remember('users', 10, function () {
            return CurrencyHistory::latest()->take(5)->get();
        });
        // Return response
        return response()->json($histories);
    }
}
