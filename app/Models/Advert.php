<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Advert extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price'
    ];

    public function image()
    {
        return $this->hasMany(AdvertImage::class);
    }

    /**
     * @param $query
     * @param array $fields
     * @return void
     */

    public function scopeSortByFields($query,array $fields)
    {
//      $q->orderBy('created_at', $this->isCorrectSort($value) ? $value : 'desc');
        $query->when(Arr::get($fields, 'price'), function ($q, $value) {
            $q->orderBy('price', $this->isCorrectSort($value) ? $value : 'desc');
        });

        $query->when(Arr::get($fields, 'created_at'), function ($q, $value) {
            $q->orderBy('created_at', $this->isCorrectSort($value) ? $value : 'desc');
        });
    }

    /**
     * @param string $direction
     * @return bool
     */
    private function isCorrectSort(string $direction)
    {
        return in_array($direction, ['asc', 'desc']);
    }
}
