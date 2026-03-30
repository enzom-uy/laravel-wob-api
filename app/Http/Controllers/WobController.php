<?php

namespace App\Http\Controllers;

use App\Models\Wob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\Console\Input\Input;

class WobController extends Controller
{
    public function test()
    {
        $wobs = Wob::all();
        return response()->json($wobs);
    }

    public function search(Request $request)
    {
        $validate = $request->validate([
            'query' => 'required|max:255',
            'page' => 'required|numeric|integer',
            'perPage' => 'required|numeric|integer|max:25'
        ]);
        $query = $request->input('query');
        $page = (int)$request->input('page', 1);
        $perPage = (int)$request->input('perPage', 10);

        $wobsText = Wob::where('text', 'like', '%' . $query . '%')->get();
        $wobsTag = Wob::whereHas('tags', function ($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%');
        })->get();

        $wobsCollection = $wobsText->merge($wobsTag)->unique('id')->values();

        $total = $wobsCollection->count();
        $results = $wobsCollection->forPage($page, $perPage)->values();


        $response = [
            'currentPage' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'lastPage' => ceil($total / $perPage),
            'data' => $results
        ];

        return response()->json($response, 200);
    }

    public function random(Request $request) {
       $request->validate([
            'afterDate' => ['nullable', 'date', 'date_format:d-m-Y', 'before:today']],
            [
                'afterDate.before' => 'Date must be before ' . today()->format('d-m-Y') . '.',
                'afterDate.date_format' => 'Formate date must be DD-MM-YYYY.'
            ]
       );

        $afterDate = $request->input('afterDate');

        $afterDateForQuery = $afterDate ? \Carbon\Carbon::createFromFormat('d-m-Y', $afterDate)->format('Y-m-d') : null;

        $randomWob = Wob::when($afterDateForQuery, fn($q) =>
            $q->where('date', '>', $afterDateForQuery)
        )->inRandomOrder()->first();
        logger($randomWob);
        $response = [
            'data' => $randomWob,
            'after' => $afterDate
        ];

        return response()->json($response, 200);
    }
}
