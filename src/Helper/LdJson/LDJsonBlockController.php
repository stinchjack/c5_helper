<?php
namespace Helper\LdJson;

use Helper\LdJson\LdJsonTrait;
use Concrete\Core\Block\BlockController;

abstract class LDJsonBlockController extends BlockController {

  use LdJsonTrait;

  public function on_start() {

    $this->makeSchema();
    parent::on_start();


  }

}
?>
