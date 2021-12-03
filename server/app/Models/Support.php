<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;

    /**
     * Get the user that supports this amendment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the amendment being supported
     */
    public function amendment()
    {
        return $this->belongsTo(Amendment::class);
    }
}
