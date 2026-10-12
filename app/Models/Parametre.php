<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Un réglage de l'établissement modifié depuis l'espace admin (voir App\Support\Etablissement).
 */
class Parametre extends Model
{
    protected $primaryKey = 'cle';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['cle', 'valeur'];
}
