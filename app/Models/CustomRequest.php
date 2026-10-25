<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CustomRequest extends Model { protected $fillable=['order_id','description','canvas_size','reference_image','status']; public function order(){return $this->belongsTo(Order::class);} }
