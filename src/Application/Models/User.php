<?php

declare(strict_types=1);

namespace App\Application\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $hidden = ['password'];

    public function setPasswordAttribute(string $value): void
    {
        if (!empty($value)) {
            $this->attributes['password'] = password_hash($value, PASSWORD_BCRYPT);
        }
    }
}
