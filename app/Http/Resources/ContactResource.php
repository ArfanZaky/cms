<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'category_id' => $this->category_id,
            'name' => isset($this->name) ? $this->name : false,
            'email' => isset($this->email) ? $this->email : false,
            'phone' => isset($this->phone) ? $this->phone : false,
            'company	' => isset($this->company) ? $this->company : false,
            'subject' => isset($this->subject) ? $this->subject : false,
            'description' => isset($this->description) ? $this->description : false,
            'status' => isset($this->status) ? $this->status : false,
        ];

        return $data;
    }
}
