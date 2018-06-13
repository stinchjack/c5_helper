<?php
namespace Helper\ComposerJson;
defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Block\BlockType\BlockType;
use Concrete\Core\Permission\Key\BlockKey;
use Concrete\Core\Page\Controller\PageTypeController;
use Helper\Permissions\BlockPermissionHelper;
use Helper\Permissions\BlockTypePermissionHelper;
/*
see this package's controller.php for usage.
Enables most of the statics to be removed from package controller.php,
instead loading version, description and handle information from composer.json.
Of course, this is only of use if the package is in a separate repo
using composer/packagist
*/

class PackageInfo {

  private $info = null;

  public function __construct ($packageFolder) {
      $jsonFile = $packageFolder . '/composer.json';
      $json = false;
      $json = @file_get_contents ($jsonFile );
      if ($json === false) {
        throw new Exception('Problem reading ' . $jsonFile);
      }
      $this->info = json_decode($json);
  }

  public function getPackageDescription() {
    return $this->info->description;
  }

  public function getVersion() {
    return $this->info->version;
  }

  public function getName() {
    return $this->info->name;
  }

  public function getHandle() {

    $xpl = explode ('/',$this->info->name);
    return $xpl[1];
  }

};
?>
