<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_user_id', 'group_id', 'total_paid', 'total_share'
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
