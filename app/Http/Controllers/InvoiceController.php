<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Counter;

class InvoiceController extends Controller
{
    public function get_all_invoices() {
        $invoices = Invoice::with('customer')->orderBY('id','DESC')->get();
        return response()->json([
            'invoices' => $invoices,
        ], 200);
    }

    public function search_invoice(Request $request){
        $search = $request->get('s');
        if($search != null){
            $invoice = Invoice::with('customer')
                 ->where('id','LIKE',"%$search%")
                 ->get();
            return response()->json([
                'invoices'=> $invoices
            ],200);
        }else{
            return $this->get_all_invoices();
        }
    }

    public function create_invoice(Request $request){
        $counter = Counter::where('key','invoice')->first();
        $random = Counter::where('key','invoice')->first();

        $invoice = Invoice::orderBy('id','Desc')->first();
        if($invoice){
            $invoice=$invoice->id+1;
            $counters = $counter->value + $invoice;
        }else{
            $counters = $counter->value;
        }

        $formData = [
            'number' => $counter->prefix.$counter,
            'customer_id' => null,
            'customer' => null,
            'date' => date('Y-m-d'),
            'due_date' => null,
            'reference' => null,
            'discount' => 0,
            'term_and_conditions' => 'Default Terms and condition',
            'items' => [
                'product_id' => null,
                'product' => null,
                'unit_price' => 0,
                'quantity' => 1
            ]
        ];
        return response()->json($formData);
    }
}
