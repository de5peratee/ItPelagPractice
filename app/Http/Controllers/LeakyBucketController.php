<?php

namespace App\Http\Controllers;

use App\Services\LeakyBucketService;

class LeakyBucketController extends Controller
{
    private LeakyBucketService $service;

    public function __construct(LeakyBucketService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('leaky-bucket.index', ['bucket' => $this->service->getState()]);
    }

    public function getState()
    {
        return response()->json($this->service->getState());
    }

    public function allowRequest()
    {
        $allowed = $this->service->allowRequest();
        return response()->json(['allowed' => $allowed, 'bucket' => $this->service->getState()]);
    }
}