<?php
namespace Helper\LdJson;

use Helper\LdJson\LdJsonTrait;
use Concrete\Core\Page\Controller\PageTypeController;

abstract class LDJsonPageTypeController extends PageTypeController {

  use LdJsonTrait;

  public function on_start() {

    $this->makeSchema();
    parent::on_start();

  }

}
?>
