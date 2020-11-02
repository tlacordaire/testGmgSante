<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Task
 *
 * @package App\Models
 *
 * @property int id
 * @property string name
 * @property string description
 * @property boolean status
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property User user_id
 */
class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    /**
     * Get the user record associated with the task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\User');
    }
}
