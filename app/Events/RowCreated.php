<?php

namespace App\Events;

use App\Models\Row;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class RowCreated implements ShouldBroadcast
{
    use SerializesModels;

    public Row $row;

    public function __construct(Row $row)
    {
        $this->row = $row;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('rows'); // публичный канал
    }

    public function broadcastAs(): string
    {
        return 'row.created';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->row->id,
            'excel_id' => $this->row->excel_id,
            'name' => $this->row->name,
            'line_number' => $this->row->line_number,
            'date' => $this->row->date->format('Y-m-d'),
        ];
    }
}
