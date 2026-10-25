<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Painting extends Model { protected $fillable=['painter_id','category_id','title','description','image','size','material','year','price','status']; protected $casts=['price'=>'decimal:2','year'=>'integer']; public function painter(){return $this->belongsTo(Painter::class);} public function category(){return $this->belongsTo(Category::class);} public function orderItems(){return $this->hasMany(OrderItem::class);} }
