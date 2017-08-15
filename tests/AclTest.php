<?php

use MatijaBelec\Acl\Acl;

class AclTest extends PHPUnit_Framework_TestCase {
  public function testRolesWithActions()
  {
    $acl = new Acl();

    // create role hierarchy
    $acl->addRole('guest');
    $acl->addRole('user', 'guest');
    $acl->addRole('administrator', 'user');

    // create actions tree
    $acl->allow('guest', 'canUpdate');
    $acl->allow('guest', 'canDelete');

    $acl->allow('user', 'canCreate');
    $acl->deny('user', 'canDelete');

    $acl->allow('administrator', ['canRead', 'canUpdate']);
    $acl->allow('administrator', 'canDelete');

    $this->assertNotTrue($acl->isAllowed('administrator', 'canRead'), 'Administrator canRead should be denied');
    $this->assertTrue($acl->isAllowed('administrator', 'canDelete'), 'Administrator canDelete should be allowed');
    $this->assertTrue($acl->isAllowed('administrator', 'canUpdate'), 'Administrator canUpdate should be allowed');
    $this->assertNotTrue($acl->isAllowed('user', 'canDelete'), 'User canDelete should be denied');
    $this->assertTrue($acl->isAllowed('user', 'canCreate'), 'User canCreate should be allowed');
    $this->assertNotTrue($acl->isAllowed('user', 'canRead'), 'User canRead should be denied');
    $this->assertNotTrue($acl->isAllowed('guest', 'canRead'), 'Guest canRead should be denied');
    $this->assertTrue($acl->isAllowed('guest', 'canDelete'), 'Guest canDelete should be allowed');
    $this->assertTrue($acl->isAllowed('guest', 'canUpdate'), 'Guest canUpdate should be allowed');

    $this->assertTrue($acl->isDenied('administrator', 'canRead'), 'Administrator canRead should be denied');
    $this->assertNotTrue($acl->isDenied('administrator', 'canDelete'), 'Administrator canDelete should be allowed');
    $this->assertNotTrue($acl->isDenied('administrator', 'canUpdate'), 'Administrator canUpdate should be allowed');
    $this->assertTrue($acl->isDenied('user', 'canDelete'), 'User canDelete should be denied');
    $this->assertNotTrue($acl->isDenied('user', 'canCreate'), 'User canCreate should be allowed');
    $this->assertTrue($acl->isDenied('user', 'canRead'), 'User canRead should be denied');
    $this->assertTrue($acl->isDenied('guest', 'canRead'), 'Guest canRead should be denied');
    $this->assertNotTrue($acl->isDenied('guest', 'canDelete'), 'Guest canDelete should be allowed');
    $this->assertNotTrue($acl->isDenied('guest', 'canUpdate'), 'Guest canUpdate should be allowed');
  }
}
