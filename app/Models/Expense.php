<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    // Mass assignment protection
    protected $fillable = ['user_id', 'category_id', 'description', 'amount', 'date'];

    // Relation 1: Expense kisi User ka hota hai
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation 2: Expense kisi Category ka hota hai
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
