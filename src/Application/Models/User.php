<?php

declare(strict_types=1);

namespace App\Application\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public const BANNED = 'banned';

    public const ACTIVE = 'active';

    public const INACTIVE = 'inactive';

    protected $table = 'users';

    protected $hidden = ['password'];

    public function scopeActive($query)
    {
        return $query->where('status', self::ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', self::INACTIVE);
    }

    public function scopeBanned($query)
    {
        return $query->where('status', self::BANNED);
    }

    public function setPasswordAttribute(string $value): void
    {
        if (!empty($value)) {
            $this->attributes['password'] = password_hash($value, PASSWORD_BCRYPT);
        }
    }
}
