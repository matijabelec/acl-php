<?php

/**
 * Acl
 *
 * @package    acl-php
 * @author     Matija Belec <matijabelec1@gmail.com>
 * @copyright  2017 Matija Belec
 * @license    MIT
 * @link       https://github.com/matijabelec/acl-php
 */

namespace MatijaBelec\Acl;

/**
 * Acl
 */
class Acl {

  /**
   * Denied action.
   * @var integer
   */
  const DENIED = 0;

  /**
   * Allowed action.
   * @var integer
   */
  const ALLOWED = 1;

  /**
   * Roles hierarchy.
   * @var array
   */
  protected $roles;

  /**
   * Actions per roles.
   * @var array
   */
  protected $actions;

  /**
   * Add allowed actions to selected role.
   * @param  string $role           role for which actions are added
   * @param  string|array $actions  actions that are added for role
   * @return void
   */
  public function allow($role, $actions) {

    // create array of actions (in any case: if $actions is string or array)
    $acts = [];
    if(is_string($actions)) {
      $acts[] = $actions;
    } else if(is_array($actions)) {
      $acts = $actions;
    }

    // check if actions for selected role are already set or create new key
    if(isset($this->actions[$role])) {
      foreach($acts as $act) {
        $this->actions[$role][$act] = Acl::ALLOWED;
      }
    } else {
      foreach($acts as $act) {
        $this->actions[$role] = [$act => Acl::ALLOWED];
      }
    }
  }

  /**
   * Add denied actions to selected role.
   * @param  string $role           role for which actions are added
   * @param  string|array $actions  actions that are added for role
   * @return void
   */
  public function deny($role, $actions) {

    // create array of actions (in any case: if $actions is string or array)
    $acts = [];
    if(is_string($actions)) {
      $acts[] = $actions;
    } else if(is_array($actions)) {
      $acts = $actions;
    }

    // check if actions for selected role are already set or create new key
    if(isset($this->actions[$role])) {
      foreach($acts as $act) {
        $this->actions[$role][$act] = Acl::DENIED;
      }
    } else {
      foreach($acts as $act) {
        $this->actions[$role] = [$act => Acl::DENIED];
      }
    }
  }

  /**
   * Check if action is allowed for selected role.
   * @param  string  $role   name of role
   * @param  string  $action name of action to be checked with
   * @return boolean         returns true if action is allowed, false otherwise
   */
  public function isAllowed($role, $action) {

    // check direct actions set for role
    if(isset($this->actions[$role]) && isset($this->actions[$role][$action])) {
      return ($this->actions[$role][$action] === Acl::ALLOWED);
    }

    // check parent actions recursively
    if(isset($this->roles[$role])) {
      $parent = $this->roles[$role];
      return isAllowed($parent, $action);
    }

    // return false (action denied is default) if action not strictly allowed
    return false;
  }

  /**
   * Check if action is denied for selected role.
   * @param  string  $role   name of role
   * @param  string  $action name of action to be checked with
   * @return boolean         returns true if action is denied, false otherwise
   */
  public function isDenied($role, $action) {
    return !($this->isAllowed($role, $action));
  }

  /**
   * Add role new role with optionally set parent.
   * @param string $role    new role name
   * @param string $parent  parent name of role or null if no parent (optional)
   * @return boolean        returns success of adding of role
   */
  public function addRole($role, $parent=null) {

    // check if parent is not set then set new role directly (value is null)
    if(is_null($parent)) {
      $this->roles[$role] = null;
      return true;
    }

    // check if valid parent is set before setting new role with parent
    if(is_string($parent) && isset($this->roles[$parent])) {
      $this->roles[$role] = $parent;
      return true;
    }

    // return false if any error occured
    return false;
  }
}
