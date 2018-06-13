<?php

namespace Concrete\Package\Helper; //<--must match package name
use Page;
use Helper\ComposerJson\PackageInfo;
class Controller extends \Package
{


    //protected $pkgHandle = 'helper'; //<--must match package name
    protected $appVersionRequired = '8.3.2';

    //protected $pkgVersion = '0.3.21';
    // see https://documentation.concrete5.org/developers/packages/adding-custom-code-to-packages
    protected $pkgAutoloaderRegistries = array(
        'src/Helper/Permissions/' => '\Helper\Permissions',
        'src/Helper/ComposerJson/' => '\Helper\ComposerJson',
        'src/Helper/Block/' => '\Helper\Block'
    );

    private function getComposerInfo() {
      if (!isset($this->packageInfo)) {
        $this->packageInfo = new PackageInfo($this->getPackagePath());
      }
      return $this->packageInfo;
    }

    public function getPackageVersion() {
      return $this->getComposerInfo()->getVersion();
    }

    public function getPackageDescription()
    {
        return t($this->getComposerInfo()->getDescription());
    }

    public function getPackageHandle()
    {
        return t($this->getComposerInfo()->getHandle());
    }

    public function getPackageName()
    {
        return t('Helper');
    }


    public function getPackageDependencies() {
      return [];
    }



  }
