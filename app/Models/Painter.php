<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Painter extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'bio',
        'photo',
        'certificate_title',
        'certificate_image',
        'certificate_description',
        'exhibition_experience',
    ];

    public function paintings()
    {
        return $this->hasMany(Painting::class);
    }
}
