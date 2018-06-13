<?php
namespace Helper\Permissions;
defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Page\Controller\PageTypeController;
use Concrete\Core\Area\Area;
use Concrete\Core\Permission\Key\AreaKey;
use Concrete\Core\Permission\Access\BlockAccess;
use Concrete\Core\User\Group\Group;
use Concrete\Core\Permission\Access\Entity\GroupEntity;
use Concrete\Core\Permission\Access\Entity\GroupCombinationEntity;
use Concrete\Core\Permission\Access\AddBlockBlockTypeAccess;

// see https://legacy-documentation.concrete5.org/tutorials/programmatically-setting-advanced-permissions


class BlockTypePermissionHelper extends PermissionHelper {

  //private $groupAccessEntities = [];
  private $block;
  private $blockAccess;

  public function __construct(Area $area, $blockTypeAllowedHandles) {

    throw new Exception ('This class doesn\'t work .. sorry!!' );

    $this->blockTypeAllowedHandles = $blockTypeAllowedHandles;
    $this->area = $area;
    $permissionKeyHandle = 'add_block_to_area';
    parent::__construct($block, $permissionKeyHandle);

  }

  protected function getPaGlobal($permissionKey) {
    //$paGlobal = \PermissionAccess::getByID
    //  ($this->permissionKey->getPermissionAccessID(), $this->permissionKey);

    //getByID($paID, Key $pk, $checkPA = true)

    return AddBlockBlockTypeAccess::create($permissionKey);

    return AddBlockBlockTypeAccess::getByID
        ($this->permissionKey->getPermissionAccessID(), $this->permissionKey);
  }

  protected function getPermissionKeyObject($permissionKeyHandle) {
    return AreaKey::getbyHandle($permissionKeyHandle);
  }

  protected function setOverridePermissions() {
    $this->area->overridePagePermissions() ;
  }
  protected function checkPermissionsSet() {
    return $this->area-> overrideCollectionPermissions();
  }

  public function savePermissions() {

    $btIDs = [];
    foreach ($this->blockTypeAllowedHandles as $bh) {
      $btIDs []= BlockType::getByHandle($bh)->getBlockTypeID();
    }

    $args = [
        'paID' => $this->access->getPermissionAccessID(),
        'btIDInclude' => [$groupAccessEntityID => $btIDs]
      ];
    //print_r($args); die();
    $this->access->save($args);

    //Get the permission reference for our page.
    $pt = $this->permissionKey->getPermissionAssignmentObject();

    //And give it our new configuration
    $pt->assignPermissionAccess($this->access);

    //and again, elevating the privileges for account holders of those organisations
    $pae = GroupCombinationEntity::getOrCreate($this->groups);
  }

}
