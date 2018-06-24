<?php
namespace Concrete\Package\Helper\Entity;

use Doctrine\ORM\Mapping as ORM;
use Concrete\Core\Support\Facade\Application;
use Spatie\SchemaOrg\Schema;
use Spatie\SchemaOrg\BaseType;

// see https://doctrine-orm.readthedocs.io/en/latest/reference/basic-mapping.html

/**
 * @ORM\Entity
 * @ORM\Table(name="GlobalDefaultLdJson")
 */
class GlobalDefaultLdJson
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     */
    protected $schemaObject;

    /**
     * @ORM\Column(type="text");
     */
    protected $ldJson;

    public static function getData() {

      $app = Application::getFacadeApplication();
      $db = $app->make('database')->connection();
      $r = $db->executeQuery('select * from GlobalDefaultLdJson  limit 1');
      $row = $r->fetch();

      if (!$row) {
        return null;
      }

      $data = new \stdObject();
      $data->schemaObject = unserialize($row['schemaObject']);
      $data->ldJson = unserialize($row['ldJson']);
      return $data;
    }

    private function updateData($schemaObject) {
      $app = Application::getFacadeApplication();
      $db = $app->make('database')->connection();
      $r = $db->executeQuery('replace into GlobalDefaultLdJson values (1, "", "")',
        array(serialize( $schemaObject)), $schemaObject->toScript());
      return $r;
    }


}

/*
    public static function getData() {

          $app = Application::getFacadeApplication();
          $db = $app->make('database')->connection();
          $r = $db->executeQuery('select * from GlobalDefaultLdJson  limit 1');
          $row = $r->fetch;

          if (!$row) {
            return null;
          }

          $data = new \stdObject();
          $data->schemaObject = unserialize($row['schemaObject']);
          $data->ldJson = unserialize($row['ldJson']);
          return $data;
    }
*/
    /*public static function setData(BaseType $schemaObject) {

          $app = Application::getFacadeApplication();
          $db = $app->make('database')->connection();
          $r = $db->executeQuery('select * from GlobalDefaultLdJson  limit 1', array());
          $row = $r->fetch;

          $data = new \stdObject();
          $data->schemaObject = unserialize($row['schemaObject']);
          $data->ldJson = unserialize($row['ldJson'];
          return $r->fetch();
    }*/


?>
