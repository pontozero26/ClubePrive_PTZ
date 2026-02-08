<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'role',
        'avatar',
        'escolheu_plano',
        'aceitou_contrato',
        'fez_pagamento',
        'is_active',
        'visivel',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function username(){
        return 'username';
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    function modelo(){
        return $this->hasOne(Modelo::class);
    }

    public function cadastroCompleto($id){
        $modelo = Modelo::where('user_id', $id)->first();

        $colunas = $modelo->getAttributes();
        foreach ($colunas as $key => $value) {
            if ($value === null && $key != 'complemento' && $key != 'deleted_at') {
                dd($key);
                return false;
            }
            return true;
        
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\CustomResetPassword($token));
    }    
}
