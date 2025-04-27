<?php

namespace App\Http\Controllers;

use App\Models\LossyCount;
use Illuminate\Http\Request;

class LossyCountController extends Controller
{
    public function index()
    {
        $frequencies = LossyCount::all();
        return view('lossycount.index', compact('frequencies'));
    }

    public function process(Request $request)
    {
        $elements = explode(',', $request->input('elements', ''));
        $windowSize = 5;
        $currentBucket = LossyCount::max('bucket') + 1 ?? 1;
        $processedElements = 0;

        foreach ($elements as $element) {
            $element = trim($element);
            if (empty($element)) continue;

            $record = LossyCount::where('element', $element)->first();

            if ($record) {
                $record->frequency += 1;
                $record->save();
            } else {
                LossyCount::create([
                    'element' => $element,
                    'frequency' => 1,
                    'bucket' => $currentBucket,
                ]);
            }

            $processedElements++;

            if ($processedElements % $windowSize === 0) {
                $records = LossyCount::all();
                foreach ($records as $record) {
                    $record->frequency -= 1;
                    if ($record->frequency <= 0) {
                        $record->delete();
                    } else {
                        $record->save();
                    }
                }
                $currentBucket++;
            }
        }

        LossyCount::where('bucket', '<', $currentBucket)->update(['bucket' => $currentBucket]);

        return redirect()->route('lossycount.index')->with('success', 'Элементы обработаны');
    }
}
