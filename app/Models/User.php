<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * @package App\Models
 *
 * @property int id
 * @property string name
 * @property string first_name
 * @property string email
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @property Collection|Task[] tasks
 */
class User extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'first_name',
        'email',
    ];

    /**
     * Get the tasks for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }
}
