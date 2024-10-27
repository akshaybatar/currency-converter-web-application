<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CurrencyHistory;
use App\Services\CurrencyService;
use Auth;
use DB;

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
            'to' => 'required|string|size:3|different:from',
            'amount' => 'required|numeric|min:1',
        ]);

        $result = $this->currencyService->convert($validated['from'], $validated['to'], $validated['amount']);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 500);
        }
        try {
            DB::beginTransaction();

            CurrencyHistory::create([
                'user_id' => Auth::user()->id,
                'from_currency' => $validated['from'],
                'to_currency' => $validated['to'],
                'amount' => $validated['amount'],
                'result' => $result['conversion_result'],
            ]);

            return response()->json(['result' => $result['conversion_result'], 'conversion_rate' => $result['conversion_rate']], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
