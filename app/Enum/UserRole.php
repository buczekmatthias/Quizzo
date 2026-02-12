<?php

namespace App\Enum;

enum UserRole: string
{
	case USER = 'user';
	case MOD = 'mod';
	case ADMIN = 'admin';
}
