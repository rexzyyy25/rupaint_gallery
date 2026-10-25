<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Payment extends Model { protected $fillable=['order_id','gateway_order_id','payment_method','payment_status','payment_proof','paid_at','snap_token','checkout_url','gateway_response']; protected $casts=['paid_at'=>'datetime','gateway_response'=>'array']; public function order(){return $this->belongsTo(Order::class);} }
