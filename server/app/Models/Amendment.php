<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amendment extends Model
{
    use HasFactory;

    /**
     * Get the amendment's assembly
     */
    public function assembly()
    {
        return $this->hasOneThrough(Assembly::class, Document::class);
    }

    /**
     * Get the amendment's document
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Get the user that registered the amendment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
