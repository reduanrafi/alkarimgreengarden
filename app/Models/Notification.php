<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'module',
        'title',
        'description',
    ];

    public function readByUsers()
    {
        return $this->belongsToMany(User::class, 'notification_user')
            ->withPivot('read_at')
            ->withTimestamps();
    }

    public function isReadBy(User $user): bool
    {
        return $this->readByUsers()
            ->where('user_id', $user->id)
            ->whereNotNull('read_at')
            ->exists();
    }
}
