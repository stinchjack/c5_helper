<?php

namespace Concrete\Package\Helper; //<--must match package name
use Page;
use Helper\ComposerJson\PackageInfo;



class Controller extends \Package
{

    protected $pkgHandle = 'exporter'; //<--must match package name
    protected $appVersionRequired = '8.3.2';
    protected $pkgVersion = '0.3.34';


    // see https://documentation.concrete5.org/developers/packages/adding-custom-code-to-packages
    protected $pkgAutoloaderRegistries = array(
        'src/Helper/Permissions/' => '\Helper\Permissions',
        'src/Helper/ComposerJson/' => '\Helper\ComposerJson',
        'src/Helper/Block/' => '\Helper\Block'
    );

    public function getPackageDescription()
    {
        return ('Jack\'s Helper PHP classes');
    }

    public function getPackageHandle()
    {
        return 'helper';
    }

    public function getPackageName()
    {
        return t('Helper');
    }


    public function getPackageDependencies() {
      return [];
    }

    public function install()
    {
        $pkg = parent::install();
        $this->installSinglePage($pkg);
    }

    public function upgrade () {

      $pkg = Package::getByHandle($this->pkgHandle);

      $exportPage=Page::getByPath('/dashboard/system/export');
      if ($exportPage) {
        $exportPage->delete(); // this works
      }

      $this->installSinglePage($pkg);

      parent::upgrade();

    }

    private function installSinglePage(&$pkg) {
      $page=Page::getByPath('/dashboard/system/export');
      if (!$page) {
        $rval = SinglePage::add('/dashboard/system/export', $pkg);
      }
    }

  }
