<?php

namespace App\Providers\Stands;

use App\Models\Stand;
use Illuminate\Contracts\View\View;

class Index
{
    public function index(): View
    {
        // Haal alle stands op en zet beschikbare stands bovenaan
        $stands = Stand::with('verkoper')
            ->orderBy('VerhuurdStatus', 'asc')
            ->get();

        return view('stands.index', [
            'stands' => $stands,
        ]);
    }
}
