<?php
namespace Helper\Permissions;
defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Page\Controller\PageTypeController;
use Concrete\Core\Permission\Key\BlockKey;
use Concrete\Core\Permission\Access\BlockAccess;
use Concrete\Core\User\Group\Group;
use Concrete\Core\Permission\Access\Entity\GroupEntity;
use Concrete\Core\Permission\Access\Entity\GroupCombinationEntity;

// see https://legacy-documentation.concrete5.org/tutorials/programmatically-setting-advanced-permissions


abstract class PermissionHelper {

  protected $groupAccessEntities = [];
  protected $groupObjects=[];
  protected $groups=[];
  protected $access;
  protected $permissionKey;

  public function __construct ($object, $permissionKeyHandle) {

    /*Avoid unncessary perssion objects in database - if the override is already set,
    dont add another set of permissions*/

    if (!$this->checkPermissionsSet() || true) {
      $this->setOverridePermissions();

      $this->permissionKey = $this->getPermissionKeyObject($permissionKeyHandle);

      $this->permissionKey->setPermissionObject($object);

      //var_dump($this->permissionKey);die();

      //$this->paGlobal = $this->getPaGlobal($permissionKey);

      $this->access = $this->getAccess($this->permissionKey);
    }
    else {
      throw new \Exception ('This helper doesn\'t work where the item alrady has override permissions set');
    }

  }

  protected function getAccess($permissionKey) {
    $this->paGlobal = $this->getPaGlobal($permissionKey);

    $access = $this->paGlobal->duplicate(); //$pa
    return $access;

  }

  abstract protected function getPermissionKeyObject($permissionKeyHandle);
  abstract protected function checkPermissionsSet();
  abstract protected function setOverridePermissions();

  public function addGroupPermission($groupName, $accessType = BlockKey::ACCESS_TYPE_INCLUDE) {

      if (!is_array($groupName) && !is_string($groupName)) {
        throw Exception ('$groupName parameter must be a string or array of strings');
      }

      if (is_string($groupName)) {
        $groupName = [$groupName];
      }

      foreach ($groupName as $gn) {
        $group = Group::getByName($gn);
        $this->groups[]=$group;
        $groupAccessEntity = GroupEntity::getOrCreate($group, false, $accessType);
        $groupAccessEntities[] =  $groupAccessEntity;
        $this->access->
          addListItem($groupAccessEntity, false, $accessType);
      }

      //array_unique($this->groups);

    }

    public function savePermissions() {

      //Save our newly created Permission Configuration
      $this->access->save(array('paID' => $this->access->getPermissionAccessID()));

      //Get the permission reference for our page.
      $pt = $this->permissionKey->getPermissionAssignmentObject();

      //And give it our new configuration
      $pt->assignPermissionAccess($this->access);

      //and again, elevating the privileges for account holders of those organisations
      $pae = GroupCombinationEntity::getOrCreate($this->groups);
    }
}
