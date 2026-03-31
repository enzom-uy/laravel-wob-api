<?php

namespace App\Http\Controllers;

use App\Models\Wob;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WobController extends Controller
{
    public function insert(Request $request)
    {
        $request->validate([
            'eventId' => ['required', 'integer'],
            'eventDate' => ['required', 'date_format:Y-m-d'],
            'tags' => ['nullable', 'string'],
            'lines' => ['required', 'array:speaker,text'],
            'note' => ['nullable', 'string']
        ]);

        $eventId = $request->input('eventId');
        // TODO: format date to global standard (d-m-Y)
        $eventDate = $request->input('eventDate');
        $tags = $request->input('tags');
        $lines = $request->input('lines');
        $note = $request->input('notes');

        // TODO: make HTML to plain text parser as in the Go DB feeder script
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|max:255',
            'page' => 'required|numeric|integer',
            'perPage' => 'required|numeric|integer|max:25',
            'afterDate' => ['nullable', 'date_format:d-m-Y', 'before:today'],
            'beforeDate' => ['nullable', 'date_format:d-m-Y', 'before:today', 'after:afterDate'],
        ], [
            'afterDate.before' => 'The afterDate field must be a date before ' . today()->format('d-m-Y') . '.',
            'afterDate.date_format' => 'The afterDate field must be in DD-MM-YYYY format.',
            'beforeDate.before' => 'The beforeDate field must be a date before ' . today()->format('d-m-Y') . '.',
            'beforeDate.date_format' => 'The beforeDate field must be in DD-MM-YYYY format.',
            'beforeDate.after' => 'The beforeDate field must be a date after afterDate.',
        ]);

        $query = $request->input('query');
        $page = (int) $request->input('page', 1);
        $perPage = (int) $request->input('perPage', 10);

        $afterDate = $request->input('afterDate')
            ? Carbon::createFromFormat('d-m-Y', $request->input('afterDate'))->format('Y-m-d')
            : null;
        $beforeDate = $request->input('beforeDate')
            ? Carbon::createFromFormat('d-m-Y', $request->input('beforeDate'))->format('Y-m-d')
            : null;

        $dateFilter = function ($q) use ($afterDate, $beforeDate) {
            $q->when($afterDate, fn($q) => $q->where('date', '>', $afterDate))
                ->when($beforeDate, fn($q) => $q->where('date', '<', $beforeDate));
        };

        $wobsText = Wob::where('text', 'like', '%' . $query . '%')
            ->tap($dateFilter)
            ->get();

        $wobsTag = Wob::whereHas('tags', function ($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%');
        })->tap($dateFilter)->get();

        $wobsCollection = $wobsText->merge($wobsTag)
            ->unique('id')
            ->sortByDesc('date')
            ->values();

        $total = $wobsCollection->count();
        $results = $wobsCollection->forPage($page, $perPage)->values();

        $response = [
            'currentPage' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'lastPage' => ceil($total / $perPage),
            'data' => $results,
        ];

        return response()->json($response, 200);
    }

    public function random(Request $request)
    {
        $request->validate(
            [
                'afterDate' => ['nullable', 'date_format:d-m-Y', 'before:today'],
                'beforeDate' => ['nullable', 'date_format:d-m-Y', 'before:today', 'after:afterDate'],
            ],
            [
                'afterDate.before' => 'The afterDate field must be a date before ' . today()->format('d-m-Y') . '.',
                'afterDate.date_format' => 'The afterDate field must be in DD-MM-YYYY format.',
                'beforeDate.before' => 'The beforeDate field must be a date before ' . today()->format('d-m-Y') . '.',
                'beforeDate.date_format' => 'The beforeDate field must be in DD-MM-YYYY format.',
                'beforeDate.after' => 'The beforeDate field must be a date after afterDate.',
            ]
        );

        $afterDate = $request->input('afterDate');
        $beforeDate = $request->input('beforeDate');

        $afterDateForQuery = $afterDate ? Carbon::createFromFormat('d-m-Y', $afterDate)->format('Y-m-d') : null;
        $beforeDateForQuery = $beforeDate ? Carbon::createFromFormat('d-m-Y', $beforeDate)->format('Y-m-d') : null;

        $randomWob = Wob::when($afterDateForQuery, fn($q) => $q->where('date', '>', $afterDateForQuery))
            ->when($beforeDateForQuery, fn($q) => $q->where('date', '<', $beforeDateForQuery))
            ->inRandomOrder()
            ->first();

        $response = [
            'data' => $randomWob,
            'afterDate' => $afterDate,
            'beforeDate' => $beforeDate,
        ];
        return response()->json($response, 200);
    }
}
