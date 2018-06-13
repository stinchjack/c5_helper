<?php
  namespace Helper\Block;
  defined('C5_EXECUTE') or die('Access Denied.');

  use Concrete\Core\Block\BlockType\BlockType;
  use Concrete\Core\Permission\Key\BlockKey;
  use Concrete\Core\Page\Controller\PageTypeController;
  use Helper\Permissions\BlockPermissionHelper;
  use Helper\Permissions\BlockTypePermissionHelper;

  class RestrictAreaBlockType {

    public static function restrictPageAreaBlockType  ($page, $area, $blockTypeHandle, $groupNames) {
      /*
      Adds a blocktype to an area, and sets permissions so that it can't be deleted.
      Intended to be called from the controller for the page. For some reason, the block
      added  doesnt show up in $area-> getAreaBlocksArray immediatley, but when it does
      it will change the permssion to that the specfied group cant delete the block
      */
      $area->setBlockLimit(1);
      $blocksInArea = $area->getTotalBlocksInArea ($page);
      if ($blocksInArea == 0) {
        $contentBlock = BlockType::getByHandle($blockTypeHandle);
        $page->addBlock($contentBlock, $area->getAreaHandle(), $data);
      }
      else {


      $block = $area-> getAreaBlocksArray()[0];

      if (!$block->overrideAreaPermissions()) {

          $helper = new BlockPermissionHelper($block, 'delete_block');

          foreach ($groupNames as $gn) {
            $helper->addGroupPermission($gn, BlockKey::ACCESS_TYPE_EXCLUDE);
          }
          $helper->savePermissions();

        }
        //$this-> removeBlockDeletePermission ($area);
      }
    }

    public static function restrictAreaBlockTypes  ($area, $blockTypeHandles, $groupNames) {

      if (!$area) return false;
      if (!$area->overrideCollectionPermissions() || true) {

          $helper = new BlockTypePermissionHelper ($area, $blockTypeHandles);

          foreach ($groupNames as $gn) {
            $helper->addGroupPermission($gn, BlockKey::ACCESS_TYPE_EXCLUDE);
          }
          $helper->savePermissions();

        }
        //$this-> removeBlockDeletePermission ($area);
        return true;
      }


  }
?>
