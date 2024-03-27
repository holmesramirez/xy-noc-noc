<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;


class User extends Model
{
    use HasFactory;
}


class User extends Authenticatable {
    use HasApiTokens;
}
