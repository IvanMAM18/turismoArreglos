<?php

namespace App\Enums;

class UserRole
{
    const USUARIO = 'usuario';
    const NEGOCIO = 'negocio';
    const ADMIN = 'admin';

    const TYPES = [
        self::USUARIO,
        self::NEGOCIO,
        self::ADMIN,
    ];
}
