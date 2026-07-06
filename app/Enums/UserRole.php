<?php

namespace App\Enums;

enum UserRole: string
{
    case Client = 'client';
    case Photographer = 'photographer';
    case Admin = 'admin';
}
