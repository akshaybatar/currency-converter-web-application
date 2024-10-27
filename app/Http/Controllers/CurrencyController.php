<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CurrencyHistory;
use App\Services\CurrencyService;

class CurrencyController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function convert(Request $request)
    {
        $validated = $request->validate([
            'from' => 'required|string|size:3',
            'to' => 'required|string|size:3',
            'amount' => 'required|numeric|min:1',
        ]);
        return response()->json(['data' => [
            'result' => 100
        ]]);
        $result = $this->currencyService->convert($validated['from'], $validated['to'], $validated['amount']);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 500);
        }

        CurrencyHistory::create([
            'from_currency' => $validated['from'],
            'to_currency' => $validated['to'],
            'amount' => $validated['amount'],
            'result' => $result['result'],
        ]);

        return response()->json($result);
    }
}
