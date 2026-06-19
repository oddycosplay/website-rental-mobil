<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'category',
        'store_id',
        'amount',
        'description',
        'attachment',
        'branch_id',
        'expense_category_id'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get Virtual Category Object for Backward Compatibility
     */
    public function getCategoryAttribute()
    {
        $catName = $this->attributes['category'] ?? 'Lainnya';
        return (object) [
            'id' => null,
            'name' => $catName,
            'slug' => \Illuminate\Support\Str::slug($catName),
        ];
    }

    /**
     * Set Category via Expense Category ID for Backward Compatibility
     */
    public function setExpenseCategoryIdAttribute(mixed $value)
    {
        if (is_numeric($value)) {
            $cat = ExpenseCategory::find($value);
            if ($cat) {
                $this->attributes['category'] = $cat->name;
            } else {
                $this->attributes['category'] = 'Lainnya';
            }
        } else {
            $this->attributes['category'] = $value;
        }
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
 
    /**
     * BACKWARD COMPATIBILITY: Relationship to Branch (maps to Store)
     */
    public function branch()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
 
    /**
     * BACKWARD COMPATIBILITY: Branch ID Accessor/Mutator
     */
    public function getBranchIdAttribute()
    {
        return $this->store_id;
    }
 
    public function setBranchIdAttribute(mixed $value)
    {
        $this->attributes['store_id'] = $value;
    }
}
