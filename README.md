
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

# Helper\\Block\\RestrictAreaBlockType

Helps restrict an Area to a single block of a single BlockType
by adding a block of the type, and removing delete Permissions
for specified groups. Requires advanced permissions to be enabled.

In the template, business as usual:
```
<?php
  $a = new Area('Header');
  $a->setAreaDisplayName('Header');
  $a->setBlockLimit(1);
  $a->display($c);  
?>
```

In the controller, the following snippet adds an 'image' blockttype to the 'Header' area if the area is empty, removing delete permissions for 'Administrators' and 'Editors' groups.

NB Administrators will still be able to delete the block and replace it with something else. Creating a separate group as such Editors will make this work.

```

use Helper\Block\RestrictAreaBlockType;

class Home extends PageTypeController
{

    public function on_start() { ... }

    public function view() {
      $imageArea = Area::get($this->page, 'Header');

      if ($imageArea) {
        RestrictAreaBlockType::restrictPageAreaBlockType
          ($this->page, Area::get($this->page, 'Header'), 'image', ['Editors', 'Administrators']);
      }

      // ... any other code ...

    }
}
```
