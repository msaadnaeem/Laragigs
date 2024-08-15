<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;
    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['tag'])) {
            $query->where('tags', 'LIKE', '%' . $filters['tag'] . '%');
        }

        if (!empty($filters['search'])) {
            $query->where(function ($query) use ($filters) {
                $query->where('title', 'LIKE', '%' . $filters['search'] . '%')
                    ->orWhere('tags', 'LIKE', '%' . $filters['search'] . '%')
                    ->orWhere('description', 'LIKE', '%' . $filters['search'] . '%');
            });
        }
    }

    //relationship to user
    public function User(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
