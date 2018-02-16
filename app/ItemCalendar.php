<?php

namespace App;

class ItemCalendar extends Model
{
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
