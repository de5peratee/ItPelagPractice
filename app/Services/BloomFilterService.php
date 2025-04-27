<?php

namespace App\Services;

use App\Models\BloomItem;

class BloomFilterService
{
    protected int $size;
    protected int $hashes;
    protected array $bitArray = [];

    public function __construct()
    {
        $this->size = config('bloomfilter.size', 1000);
        $this->hashes = config('bloomfilter.hashes', 3);
        $this->initialize();
    }

    protected function initialize(): void
    {
        $this->bitArray = array_fill(0, $this->size, 0);

        foreach (BloomItem::all() as $item) {
            foreach ($item->hashes as $hash) {
                $this->bitArray[$hash] = 1;
            }
        }
    }

    public function add(string $item): array
    {
        if (!BloomItem::where('item', $item)->exists()) {
            $hashes = $this->getHashes($item);
//            dd($item);
            BloomItem::create([
                'item' => $item,
                'hashes' => $hashes,
            ]);
            foreach ($hashes as $hash) {
                $this->bitArray[$hash] = 1;
            }
            return $hashes;
        }
        return $this->getHashes($item);
    }

    public function exists(string $item): bool
    {
        foreach ($this->getHashes($item) as $hash) {
            if ($this->bitArray[$hash] === 0) {
                return false;
            }
        }
        return true;
    }

    public function getHashes(string $item): array
    {
        $hashes = [];
        for ($i = 0; $i < $this->hashes; $i++) {
            $hash = crc32($item . $i) % $this->size;
            $hashes[] = abs($hash);
        }
        return $hashes;
    }
}