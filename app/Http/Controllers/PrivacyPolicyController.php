<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    public function index()
    {
        
        $data = [
            'page_title' => 'Privacy Policy',
            'company_name' => config('app.name', 'Sadhna Weekly'),
            'effective_date' => now()->format('d M Y'),
        ];
        return view('privacy-policy', $data);
    }
}