<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class DocumentResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray( $request ) {
        $this->link = $this->link ?? (isset($this->path) ? (URL::to($this->path)) : '');
        $array      = [
            'id'    => $this->id,
            'title' => $this->title,
            'link'  => $this->link,
            'meta'  => [
                'type'        => $this->mime,
                'size'        => helperFileSize( $this->size ),
                'uploaded_at' => Carbon::createFromTimeString( $this->created_at )->diffForHumans( Carbon::now() ),
            ],
        ];
        if ( in_array( $this->mime, [ 'image/jpeg', 'image/gif', 'image/png' ] ) ) {
            $array['sizes'] = [
                'large'     => str_replace( '/original/', '/large/', $this->link ),
                'medium'    => str_replace( '/original/', '/medium/', $this->link ),
                'thumbnail' => str_replace( '/original/', '/thumbnail/', $this->link ),
            ];
        }

        return $array;
    }
}
