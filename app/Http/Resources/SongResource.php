<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class SongResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'composer' => $this->composer,
            'thumbnail' => $this->thumbnail,
            'singer' => $this->singer,
            'lyric'=>$this->lyric,
            'user' => new UserResource($this->user)
//            'user' => UserResource::collection($this->whenLoaded('user'))
        ];
    }
}
