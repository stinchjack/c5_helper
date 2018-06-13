<?php

namespace Concrete\Package\Helper; //<--must match package name
use Page;
use Helper\ComposerJson\PackageInfo;



class Controller extends \Package
{

    protected $pkgHandle = 'exporter'; //<--must match package name
    protected $appVersionRequired = '8.3.2';
    protected $pkgVersion = '0.3.33';


    // see https://documentation.concrete5.org/developers/packages/adding-custom-code-to-packages
    protected $pkgAutoloaderRegistries = array(
        'src/Helper/Permissions/' => '\Helper\Permissions',
        'src/Helper/ComposerJson/' => '\Helper\ComposerJson',
        'src/Helper/Block/' => '\Helper\Block'
    );

    public function getPackageDescription()
    {
        return ('bananas');
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



  }
