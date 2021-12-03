<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'last_name',
        'DNI',
        'email',
        'password',
        'data'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the amendments registered by the user
     */
    public function amendments()
    {
        return $this->hasMany(Amendment::class);
    }

    /**
     * Get the amendment being supported by the user
     */
    public function supports()
    {
        return $this->hasMany(Support::class);
    }

    /**
     * Check if a user can attend an assembly
     */
    public function canAttend($assembly)
    {
        $filters = json_decode($assembly->filter);
        $userdata = json_decode($this->data);
        $checks = true;

        foreach($filters as $filterName => $filterValue) {
            if ($userdata->$filterName != $filterValue) {
                $checks = false;
            }
        }

        return $checks;
    }

    /**
     * Fail if user cannot attend an assembly
     */
    public function checkAttendance($assembly) {
        if(!$this->canAttend($assembly)) {
            abort(405, 'No tens accés a aquest document');
        }
    }

    /**
     * Get user data
     */
    public function data($field)
    {
        $data = json_decode($this->data);
        return $data->$field;
    }

    /**
     * Get user full name
     */
    public function fullName()
    {
        return $this->name . ' ' . $this->last_name;
    }
}
