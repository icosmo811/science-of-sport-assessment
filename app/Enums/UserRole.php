<?php

namespace App\Enums;

enum UserRole: string
{
    // Define user roles as enum cases
    case ADMIN = 'admin';
    case EDITOR = 'editor';
}
