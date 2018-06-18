<?php
namespace Helper\LdJson;

use Config;
use Spatie\SchemaOrg\Schema;
use Spatie\SchemaOrg\BaseType;
use Helper\Get\Get;

trait LdJsonTrait {

  abstract protected function schemaType() : string;
  abstract protected function schemaProperties($fieldData) : array;


  private  function makeSchema() {


    $this->env = Config::getEnvironment();


    $type = trim($this->schemaType());

    $this->schemaObject = Schema::$type();

    //$this->setSchemaId();

    $fieldData = $this->getDbfields();

    $propertyValues = $this->schemaProperties($fieldData);

    //die(implode (', ', array_keys($propertyValues)));
    foreach ($propertyValues as $propertyName=>$value) {
      $this->schemaObject->$propertyName($value);
    }

    $this->setSchemaId();

    $this->set('LDJson', $this->getLDJson());
  }


  private function getDbfields() {
    if (is_a ($this, 'Concrete\Core\Block\BlockController') ||
        is_subclass_of ($this,  'Concrete\Core\Block\BlockController')){
      $fieldData = Get::blockControllerFields($this);

      return $fieldData;

    }
    else {

      return null;
    }
  }

  private function setSchemaId($schemaObject = null){
    if (!$schemaObject) {
      $schemaObject = $this->schemaObject;
    }
    $page = \Page::getCurrentPage();
    if (!$page) {
      return false;
    }
    $id = $page->getCollectionLink(true); //always use page URL as id
    $schemaObject->setProperty('@id', $id);
    return true;
  }

  protected function makeSchemaProperty($type, $propertyValues=[]) {

    $type = trim($type);
    $subSchema = Schema::$type();

    foreach ($propertyValues as $propertyName=>$value) {
      $subSchema->$propertyName($value);
    }
    return $subSchema;
  }

  private function setSchemaProperty($schemaObject, $property, $value) {
    $schemaObject->$property($value);
  }

  public function getLDJson() {
    return $this->schemaObject->toScript();
  }


}
