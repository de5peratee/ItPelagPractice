<?php

return [
    'size' => env('BLOOMFILTER_SIZE', 1000),
    'hashes' => env('BLOOMFILTER_HASHES', 3),
];
