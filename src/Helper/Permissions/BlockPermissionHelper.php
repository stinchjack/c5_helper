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


class BlockPermissionHelper extends PermissionHelper {

  //private $groupAccessEntities = [];
  private $block;
  private $blockAccess;


  public function __construct(\Block $block, $permissionKeyHandle = 'delete_block') {

    $this->block = $block;

    parent::__construct($block, $permissionKeyHandle);

  }

  protected function getPermissionKeyObject($permissionKeyHandle) {
    return BlockKey::getbyHandle($permissionKeyHandle);
  }

  protected function setOverridePermissions() {
    $this->block->doOverrideAreaPermissions();
  }
  protected function checkPermissionsSet() {
    return $this->block->overrideAreaPermissions();
  }

}
