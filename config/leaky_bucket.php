<?php

//return [
//    'capacity' => env('LEAKY_BUCKET_CAPACITY', 10),
//    'leak_rate' => env('LEAKY_BUCKET_LEAK_RATE', 1),
//    'time_window' => env('LEAKY_BUCKET_TIME_WINDOW', 1),
//];


return [
    'capacity' => 10,
    'leak_rate' => 2,
    'time_window' => 5,
];