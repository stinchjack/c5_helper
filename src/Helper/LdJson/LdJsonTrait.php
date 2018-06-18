<?php
namespace Helper\LdJson;

use Config;
use Spatie\SchemaOrg\Schema;
use Spatie\SchemaOrg\BaseType;
use Helper\Get\Get;

trait LdJsonTrait {

  abstract protected function schemaType() : string;
  abstract protected function schemaProperties() : array;


  private  function makeSchema() {


    $this->env = Config::getEnvironment();


    $type = trim($this->schemaType());

    $this->schemaObject = Schema::$type();

    //$this->setSchemaId();

    $fieldData = $this->getDbfields();
    $this->setRootSchemaProperties($fieldData);
    //$this->set('LdJson', $this->getLDJson());


  }
  private function getDbfields() {
    if ($this instanceof Concrete\Core\Block\BlockController) {
      $fieldData = Get::blockControllerFields($this);
      return $fieldData;
    }
  }

  private function setSchemaId($schema){
    $page = \Page::getCurrentPage();
    if (!$page) {
      return false;
    }
    $id = $page->getCollectionLink(true); //always use page URL as id
    $this->schemaObject->setProperty('@id', $id);
    return true;
  }

  protected function makeSchemaProperty($type, $propertyValues=[]) {
    $subSchema = $this->makeSchema($type);

    if ($type instanceof BaseType) {
      $subSchema = $type;
    }
    else {
      $type = trim($type);
      $subSchema = Schema::$type();
    }
    foreach ($propertyValues as $propertyName=>$value) {
      $subSchema->$property($value);
    }
  }

  private function setRootSchemaProperties() {

    $propertyValues = $this->schemaProperties();
    die("zzzzzzz");

    die(implode (', ', array_keys($propertyValues)));
    foreach ($propertyValues as $propertyName=>$value) {
      $this->schemaObject->$propertyName($value);
    }
  }



  private function setSchemaProperty($schemaObject, $property, $value) {
    $schemaObject->$property($value);
  }

  public function getLDJson() {
    return $this->schemaObject->toScript();
  }


}
