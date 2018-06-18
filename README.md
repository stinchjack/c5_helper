
# LDJson Helper classes

## Helper\\LdJson\\LDJsonBlockController

Use in place of `Concrete\Core\Block\BlockController`,
implementing schemaType() and  schemaProperties($fieldData) methods.

e.g

```
namespace Concrete\Package\Extrablocks\Block\Contactdetails;
use Helper\LdJson\LDJsonBlockController;

class Controller extends LDJsonBlockController
{

      protected $btTable = ' ... ';
      public function getBlockTypeName() { ... }


      public function getBlockTypeDescription() { ... }

      protected function schemaType() : string {
        return "ContactPoint";
      }

      protected function schemaProperties($fieldData) : array {

        // $fielddata contains the row from the BlockTypeController's table

        $name = trim($fieldData['honorific'] . ' ' .
          $fieldData['firstName'] . ' ' . $fieldData['lastName']);

        $data = [];

        $data['name'] = $name;

        $data['areaServed'] = $this->makeSchemaProperty(
            'AdministrativeArea',
              ['address' => '123 bonsqde st, Wellington',
              'branchCode'=> 'JW001']
          );

        if ($fieldData['email']) {
          $data['email'] = $fieldData['email'];
        }
        if ($fieldData['phone']) {
          $data['telephone'] = $fieldData['phone'];
        }
        if ($fieldData['fax']) {
          $data['fax'] = $fieldData['fax'];
        }

        return $data;
      }

      public function view($args) {...}
      public function validate($args) {...}

}

```

In view.php, add:

```
  <?php
    echo $LDJson;
  ?>
```

## Helper\\LdJson\\LDJsonBlockController

Use in place of `Concrete\Core\Block\BlockController`,
implementing schemaType() and  schemaProperties($fieldData) methods.

e.g

```
namespace Concrete\Package\Extrablocks\Block\Contactdetails;
use Helper\LdJson\LDJsonBlockController;

class Full extends PageTypeController
{

    public function on_start() { ... }
    protected function schemaType() : string {
        return "LocalBusiness";
      }

    protected function schemaProperties($fieldData) : array {
      // $fielddata is null

      $data = [];
      $data['name'] = $name;
      return $data;
    }

    public function view($args) {...}


}

```

In view.php, add:

```
  <?php
    echo $LDJson;
  ?>
```
