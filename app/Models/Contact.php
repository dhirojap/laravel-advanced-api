<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = "contacts";
    protected $primaryKey = "id";
    protected $keyType = "contacts";
    protected $incrementing = true;
    protected $timestamps = true;

    protected $fillable = [
        "first_name",
        "last_name",
        "email",
        "phone"
    ];
}
