<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Amendment extends Model
{
    use HasFactory;

    protected $appends = ['url'];

    /**
     * Boot
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->ref = Str::random(10);
        });
    }

    /**
     * Get the amendment's document
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Get amendment signatures
     */
    public function supports()
    {
        return $this->hasMany(Support::class);
    }

    /**
     * Get the user that registered the amendment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the amendment's url
     */
    public function getUrlAttribute()
    {
        return config('esmenes.frontend_url') . '/esmena/' . $this->ref;
    }
}
