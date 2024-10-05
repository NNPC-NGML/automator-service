<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\UnitUser;
use App\Models\DesignationUser;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Skillz\Nnpcreusable\Models\User as ModelsUser;


class User extends ModelsUser
{
    public function usersUnit()
    {
        return $this->hasOne(UnitUser::class, 'user_id'); // Assuming user_id is the foreign key
    }

    public function userDesignation()
    {
        return $this->hasOne(DesignationUser::class, 'user_id'); // Assuming user_id is the foreign key
    }
}
