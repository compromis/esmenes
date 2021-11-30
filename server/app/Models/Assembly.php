<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assembly extends Model
{
    use HasFactory;

    /**
     * Get the amendment's documents
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
