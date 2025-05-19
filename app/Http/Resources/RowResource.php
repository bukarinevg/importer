<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request) : array
    {
        return [
            'excel_id' => $this->excel_id,
            'name' => $this->name,
            'line_number' => $this->line_number,
            'date' => $this->date,
        ];
    }
}
