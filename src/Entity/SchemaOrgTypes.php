<?php
namespace Concrete\Package\Helper\Entity;

use Doctrine\ORM\Mapping as ORM;
use Concrete\Core\Support\Facade\Application;
use Spatie\SchemaOrg\Schema;
use Spatie\SchemaOrg\BaseType;
use \Concrete\Core\Package\Package;

/**
 * @ORM\Entity
 * @ORM\Table(name="SchemaOrgTypes")
 */
class SchemaOrgTypes
{

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Id @ORM\Column(type="date")
     */
    protected $insertDate;

    /**
     * @ORM\Id @ORM\Column(type="string")
     */
    protected $schemaOrgId;

    /**
     * @ORM\Column(type="string")
     */
    protected $label;

    /**
     * @ORM\Column(type="text");
     */
    protected $comment;

    /**
     * @ORM\Column(type="string");
     */
    protected $enumerationtype;

    /**
     * @ORM\Column(type="string");
     */
    protected $equivalentClass;

    /**
     * @ORM\Column(type="text");
     */
    protected $subTypes;

    /**
     * @ORM\Column(type="text");
     */
    protected $supersedes;

    /**
     * @ORM\Column(type="text");
     */
    protected $supersededBy;

    /**
     * @ORM\Column(type="text");
     */
    protected $isPartOf;

    private function checkDBSetup() {
      $app = Application::getFacadeApplication();
      $db = $app->make('database')->connection();
      $r = $db->executeQuery('select insertDate from  SchemaOrgTypes  limit 1');
      if (!$r) {
        return false;
      }
      $row = $r->fetch();
      if (!$row) {
        return false;
      }
      return strtotime($row['insertDate']);
    }

    private function csvFileName() {
      $packagePath = Package::getByID('handle')->getPackagePath();
      $csvFile = $packagePath . '/files/schema-types.csv';
      return $csvFile;
    }

    private function checkSetup() {

      $csvFile = $packagePath . '/files/schema-types.csv';

      $csvTimeStamp = filemtime($this->csvFileName());
      if (!$csvTimeStamp) {
        throw new Exception ('Could not check timestamp of' . $csvFile);
      }

      $dbResult = checkDBSetup(); //timestamp or fail

      $setupDb =  !$dbResult || $dbResult < $csvTimeStamp;

      if ($setupDb) {
        $this->setupDb();
      }

    }

    private function setupDb() {
      $this->truncate();
      $data = $this->readCSV();
      $this->insertData($data);
    }

    private function truncate() {
      $app = Application::getFacadeApplication();
      $db = $app->make('database')->connection();
      $r = $db->executeQuery('delete from SchemaOrgTypes where 1=1');
      return;
    }

    private function insertData($dataRows) {
      $app = Application::getFacadeApplication();
      $db = $app->make('database')->connection();


      /* SchemaOrg,id,label,comment,subTypeOf,enumerationtype,equivalentClass
      properties,subTypes,supersedes,supersededBy,isPartOf */

      $id  = 0;
      foreach ($dataRows as $rows) {
        array_unshift($row, $id);
        $db->insert("SchemaOrgTypes");

        $id ++;
      }

    }

    private function readCSV($filename) {
      $csvFile = fopen($filename, "r");
      if (!$csvFile) {
        return false;
      }

      //1st row contains row headers
      $columnTitles = fgetcsv($fileHandle, 0, ",");

      $data = [];

      while (($row = fgetcsv($fileHandle, 0, ",")) !== FALSE) {

        $data[] = $row;

      }
      return $data;
    }



}



?>
