<?php

namespace App\Http\Controllers;

use App\Models\Wob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WobController extends Controller
{
    public function test()
    {
        $wobs = Wob::all();
        return response()->json($wobs);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $page = $request->input('page', 1);
        $perPage = 10;

        $wobsText = Wob::where('text', 'like', '%' . $query . '%')->get();
        $wobsTag = Wob::whereHas('tags', function ($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%');
        })->get();

        $wobsCollection = $wobsText->merge($wobsTag)->unique('id')->values();

        $total = $wobsCollection->count();
        $results = $wobsCollection->forPage($page, $perPage)->values();


        $response = [
            'data' => $results,
            'currentPage' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'lastPage' => ceil($total / $perPage)
        ];

        return response()->json($response, 200);
    }
}
