<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['role_id', 'name', 'phone', 'company', 'is_favorite'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_favorite' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];
    
    /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array
     */
    protected $appends = ['role_name'];

    /**
     * Get the role that owns the contact.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the role name for the contact.
     *
     * @return string
     */
    public function getRoleNameAttribute()
    {
        return $this->role ? $this->role->name : 'No Role';
    }
}
