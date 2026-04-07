<?php

namespace App\Enums;

/**
 * User Role Enumeration
 *
 * Backed string enum — use ->value to get the raw string (e.g. UserRole::ADMIN->value === 'admin').
 * All static helpers accept plain strings so existing callers are unchanged.
 */
enum UserRole: string
{
    case SUPERADMIN = 'superadmin';
    case ADMIN = 'admin';
    case PROJECT_MANAGER = 'project_manager';
    case USER = 'user';
    case AUTHOR = 'author';

    /**
     * All role values as plain strings.
     */
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Role values that are considered administrative.
     */
    public static function adminRoles(): array
    {
        return [self::SUPERADMIN->value, self::ADMIN->value];
    }

    /**
     * Check if a plain-string role is an admin role.
     */
    public static function isAdmin(string $role): bool
    {
        return in_array($role, self::adminRoles());
    }

    /**
     * Human-readable labels keyed by role value.
     */
    public static function labels(): array
    {
        return [
            self::SUPERADMIN->value    => 'Super Administrator',
            self::ADMIN->value         => 'Administrator',
            self::PROJECT_MANAGER->value => 'Project Manager',
            self::USER->value          => 'User',
            self::AUTHOR->value        => 'Author',
        ];
    }

    /**
     * Human-readable label for a specific role string.
     */
    public static function label(string $role): string
    {
        return self::labels()[$role] ?? ucfirst($role);
    }
}
