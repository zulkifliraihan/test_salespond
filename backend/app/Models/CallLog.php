<?php

namespace App\Models;

use App\Enums\StatusCallLogEnum;
use Illuminate\Database\Eloquent\Model;

class CallLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['contact_id', 'duration', 'status'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => StatusCallLogEnum::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the contact that owns the call log.
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
