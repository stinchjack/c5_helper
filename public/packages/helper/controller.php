<?php

namespace Concrete\Package\Helper; //<--must match package name
use Page;

class Controller extends \Package
{
    protected $pkgHandle = 'helper'; //<--must match package name
    protected $appVersionRequired = '8.3.2';
    protected $pkgVersion = '0.2.8';

    // see https://documentation.concrete5.org/developers/packages/adding-custom-code-to-packages
    protected $pkgAutoloaderRegistries = array(
        'src/Helper/Permissions/' => '\Helper\Permissions',
        'src/Helper/Block/' => '\Helper\Block'
    );


    public function getPackageDescription()
    {
        return t('Jack\'s Helper classes');
    }

    public function getPackageName()
    {
        return t('Helper');
    }


  }
