<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

//use Maatwebsite\Excel\Concerns\withHeadings;

class productsExport implements FromCollection,WithHeadings
{
    public function headings(): array
    {
        return [
            'title',
            'id',
            'override',
            'availability',
            'price',
            'sale_price',
        ];
    }
    public function collection()
    {
//        return Product::all();
        return collect(Product::getProduct());
    }
}
