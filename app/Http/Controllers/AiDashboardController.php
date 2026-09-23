<?php

namespace App\Http\Controllers;

use App\Services\AiService;
use Illuminate\Http\Request;

class AiDashboardController extends Controller
{
    public function __construct(private AiService $aiService) {}

    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $result = $this->aiService->handle($request->user(), $validated['message']);

        return response()->json([
            'reply' => $result,
        ]);
    }
}
