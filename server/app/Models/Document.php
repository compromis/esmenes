<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    /**
     * Get the document's assembly
     */
    public function assembly()
    {
        return $this->belongsTo(Assembly::class);
    }

    /**
     * Get the document's amendments
     */
    public function amendments()
    {
        return $this->hasMany(Amendment::class);
    }
}
