<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvertResource extends JsonResource
{
    public function selectByFields(string $fieldName)
    {
        return $this->when(in_array($fieldName, request()->get('fields', [])), $this->{$fieldName});
    }
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $images = AdvertImgResource::collection($this->image);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'price' => $this->price,
            'description'=> $this->selectByFields('description'),
            'images' => $this->selectByFields('image'),
            'first_image' => $images->first(),
//            'created_at' => $this->created_at->format('d.m.y')
        ];
    }
}
