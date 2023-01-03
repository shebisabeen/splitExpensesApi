<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id', 'description', 'amount', 'payer', 'split'
    ];

    public function groupMembers()
    {
        return $this->belongsTo(GroupMember::class);
    }
}
