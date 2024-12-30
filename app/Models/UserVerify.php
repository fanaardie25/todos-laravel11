<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVerify extends Model
{
    protected $table = 'password_reset_tokens';
    protected $fillable = ['email', 'token'];

    const UPDATED_AT = null;
}
