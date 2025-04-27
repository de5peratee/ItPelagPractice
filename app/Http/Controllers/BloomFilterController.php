<?php

namespace App\Http\Controllers;

use App\Models\BloomItem;
use Illuminate\Http\Request;
use App\Services\BloomFilterService;

class BloomFilterController extends Controller
{
    protected BloomFilterService $bloomFilterService;

    public function __construct(BloomFilterService $bloomFilterService)
    {
        $this->bloomFilterService = $bloomFilterService;
    }

    public function index()
    {
        $itemsWithHashes = BloomItem::all();

        return view('bloom.index', compact('itemsWithHashes'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'item' => 'required|string',
        ]);

        $hashes = $this->bloomFilterService->add($request->item);

        return back()->with([
            'success' => 'Элемент добавлен в Bloom Filter',
            'hashes' => $hashes,
        ]);
    }

    public function check(Request $request)
    {
        $request->validate([
            'item' => 'required|string',
        ]);

        $exists = $this->bloomFilterService->exists($request->item);
        $hashes = $this->bloomFilterService->getHashes($request->item);

        return back()->with([
            'status' => $exists ? 'Элемент существовует' : 'Элемент не существует',
            'hashes' => $hashes,
        ]);
    }
}