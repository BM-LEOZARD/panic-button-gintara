<?php

use App\Models\TugasAdmin;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('superadmin', function ($user) {
    return $user->role === 'SuperAdmin';
});

Broadcast::channel('admin.{userId}', function ($user, $userId) {
    return $user->role === 'Admin' && (int) $user->id === (int) $userId;
});

Broadcast::channel('wilayah.{wilayahId}', function ($user, $wilayahId) {
    if ($user->role === 'Admin') {
        return TugasAdmin::where('user_id', $user->id)
            ->where('wilayah_cover_id', $wilayahId)
            ->exists();
    }
    return false;
});

Broadcast::channel('pelanggan.{pelangganId}', function ($user, $pelangganId) {
    return $user->pelanggan && (int) $user->pelanggan->id === (int) $pelangganId;
});
