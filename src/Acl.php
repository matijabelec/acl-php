<?php

declare(strict_types=1);

/**
 * Acl
 *
 * @package    acl-php
 * @author     Matija Belec <matija@belec.dev>
 * @copyright  2017 Matija Belec
 * @license    MIT
 * @link       https://github.com/matijabelec/acl-php
 */

namespace MatijaBelec\Acl;

final class Acl
{
    /**
     * @var int
     */
    public const DENIED = 0;

    /**
     * @var int
     */
    public const ALLOWED = 1;

    protected array $roles;

    protected array $actions;

    public function allow(string $role, string|array $actions): void
    {

        // create array of actions (in any case: if $actions is string or array)
        $acts = [];
        if (is_string($actions)) {
            $acts[] = $actions;
        } else if (is_array($actions)) {
            $acts = $actions;
        }

        // check if actions for selected role are already set or create new key
        if (isset($this->actions[$role])) {
            foreach ($acts as $act) {
                $this->actions[$role][$act] = Acl::ALLOWED;
            }
        } else {
            foreach ($acts as $act) {
                $this->actions[$role] = [$act => Acl::ALLOWED];
            }
        }
    }

    public function deny(string $role, string|array $actions): void
    {

        // create array of actions (in any case: if $actions is string or array)
        $acts = [];
        if (is_string($actions)) {
            $acts[] = $actions;
        } else if (is_array($actions)) {
            $acts = $actions;
        }

        // check if actions for selected role are already set or create new key
        if (isset($this->actions[$role])) {
            foreach ($acts as $act) {
                $this->actions[$role][$act] = Acl::DENIED;
            }
        } else {
            foreach ($acts as $act) {
                $this->actions[$role] = [$act => Acl::DENIED];
            }
        }
    }

    public function isAllowed(string $role, string $action): bool
    {

        // check direct actions set for role
        if (isset($this->actions[$role]) && isset($this->actions[$role][$action])) {
            return ($this->actions[$role][$action] === Acl::ALLOWED);
        }

        // check parent actions recursively
        if (isset($this->roles[$role])) {
            $parent = $this->roles[$role];
            return $this->isAllowed($parent, $action);
        }

        // return false (action denied is default) if action not strictly allowed
        return false;
    }

    public function isDenied(string $role, string $action): bool
    {
        return !($this->isAllowed($role, $action));
    }

    public function addRole(string $role, ?string $parent = null): bool
    {

        // check if parent is not set then set new role directly (value is null)
        if (is_null($parent)) {
            $this->roles[$role] = null;
            return true;
        }

        // check if valid parent is set before setting new role with parent
        if (is_string($parent) && isset($this->roles[$parent])) {
            $this->roles[$role] = $parent;
            return true;
        }

        // return false if any error occurred
        return false;
    }
}
