<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'full_name',
    'bio',
    'phone',
    'address',
    'job_title',
    'company',
])]
#[Table('user_profiles')]
class UserProfile extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
