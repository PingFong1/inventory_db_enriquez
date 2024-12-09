<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Support\Str;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description', 
        'category',
        'department',
        'current_quantity',
        'minimum_quantity',
        'maximum_quantity',
        'usage_frequency',
        'budget_category',
        'unit_price',
        'unit_type',
        'sku',
        'status',
        'barcode',
        'image'
    ];

    protected $attributes = [
        'status' => 'available',
        'minimum_quantity' => 0
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($item) {
            // Generate SKU if not provided
            if (empty($item->sku)) {
                $item->sku = Str::upper(Str::random(8));
            }
            
            // Generate barcode if not provided
            if (empty($item->barcode)) {
                $item->barcode = date('Y') . str_pad($item->id ?? 
                    (self::max('id') + 1), 8, '0', STR_PAD_LEFT);
            }
        });
    }

    public function updateStatus()
    {
        if ($this->current_quantity <= 0) {
            $this->status = 'out_of_stock';
        } elseif ($this->current_quantity <= $this->minimum_quantity) {
            $this->status = 'low_stock';
        } else {
            $this->status = 'available';
        }
        $this->save();
    }

    public function stockIn($quantity, $remarks = null)
    {
        $this->current_quantity += $quantity;
        $this->updateStatus();
        
        // Record stock movement using admin guard
        StockMovement::create([
            'item_id' => $this->id,
            'type' => 'in',
            'quantity' => $quantity,
            'remarks' => $remarks,
            'performed_by' => auth()->guard('admin')->id()
        ]);
    }

    public function stockOut($quantity, $remarks = null)
    {
        if ($this->current_quantity >= $quantity) {
            $this->current_quantity -= $quantity;
            $this->updateStatus();
            
            // Record stock movement using admin guard
            StockMovement::create([
                'item_id' => $this->id,
                'type' => 'out',
                'quantity' => $quantity,
                'remarks' => $remarks,
                'performed_by' => auth()->guard('admin')->id()
            ]);
            
            return true;
        }
        return false;
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path 
            ? asset('storage/' . $this->image_path)
            : asset('images/no-image.png');
    }

    public function requests(): HasMany
    {
        return $this->hasMany(InventoryRequest::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function departmentRelation(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department', 'name');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format($this->unit_price, 2);
    }
}