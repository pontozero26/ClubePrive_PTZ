<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Precadastro extends Model
{
    use HasFactory;

    protected $table = "precadastro";

    protected $fillable = [
        "nome",
        "data_nascimento",
        "telefone",
        "email",
        "senha",
        "cpf",
    ] ;
}
