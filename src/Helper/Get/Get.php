<?php
namespace Helper\Get;
defined('C5_EXECUTE') or die('Access Denied.');

class Get {

  /*
    return database row associated with BlockController object
  */
  public static function blockControllerFields($blockControllerObject) {
    $blockObject = $blockControllerObject->getBlockObject();
    $bId = $blockObject->getBlockID();
    $dbTable = $blockControllerObject->getBlockTypeDatabaseTable();

    $app = Application::getFacadeApplication();
    $db = $app->make('database')->connection();
    $r = $db->executeQuery("select * from ? where bID = ?",
      array($dbTable, $bId));

    return $r->fetch();
  }
}

?>
