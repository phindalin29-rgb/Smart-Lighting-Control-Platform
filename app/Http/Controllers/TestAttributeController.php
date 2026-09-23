<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Attributes\Route;

class TestAttributeController extends Controller
{
    #[Route('/test-attr', name: 'test.attr')]
    public function index()
    {
        return 'ok';
    }
}
