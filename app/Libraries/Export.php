<?php

namespace App\Libraries;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class Export implements FromView
{
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('applications.excel', [
            'data' => $this->data
        ]);
    }
}