<?php

namespace App\Http\Controllers;

use App\Services\AiService;
use Illuminate\Http\Request;

class AiAssistantController extends Controller
{
    public function __construct(private AiService $aiService) {}

    public function index()
    {
        return view('ai.index');
    }

    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $result = $this->aiService->handle($request->user(), $validated['message']);

        return back()
            ->with('ai_result', $result)
            ->withInput();
    }
}
