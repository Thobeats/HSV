<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class HSV extends Authenticatable implements JWTSubject
{
    protected $table = "table__h_s_v_users";

    protected $fillable = [
        "email",
        "password",
        "name"
    ];

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }
}
