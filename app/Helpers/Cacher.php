<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class Cacher
{
    public function __construct(public string $store = 'file')
    {
    }

    public function setCached($key, $value)
    {
        $cachedData = Cache::store($this->store)->put($key, $value, now()->addMinutes(20));
    }

    public function getCached($key)
    {
        $cachedData = Cache::store($this->store)->get($key);
        if ($cachedData) {
            return $cachedData;
        }
    }

    public function removeCached($key)
    {
        Cache::store($this->store)->forget($key);
    }
}