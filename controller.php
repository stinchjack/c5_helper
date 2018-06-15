<?php

namespace Concrete\Package\Helper; //<--must match package name
use Page;
use Package;
use Config;
use SinglePage;

class Controller extends \Package
{

    protected $pkgHandle = 'exporter'; //<--must match package name
    protected $appVersionRequired = '8.3.2';
    protected $pkgVersion = '0.4.14';


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

    private function setup() {
      $this->env = Config::getEnvironment();
      if ($this->env == 'local') {
          $this->verbose = true;
      }

    }

    public function install() {
      $this->setup();
      $pkg = parent::install();
      if ($this->env == 'local') {
        $this->installSinglePage($pkg);
      }
    }

    public function upgrade () {
      $this->setup();
      $pkg = Package::getByHandle($this->pkgHandle);

      if ($this->env == 'local') {
        $page=Page::getByPath('/dashboard/system/modulechecker');
        if ($page) {
          if ($this->verbose) {
            print "deleting previous version ... \r\n";
          }
          $page->delete(); // this works
        }

        $this->installSinglePage($pkg);
      }

      parent::upgrade();

    }

    private function installSinglePage(&$pkg) {
      if ($this->verbose) {
        print "install SinglePage function ... \r\n";
      }

      $page=Page::getByPath('/dashboard/system/modulechecker');
      if ($page) {
        $page->delete();
      }


      if ($this->verbose) {
        print "adding singlepage ... \r\n";
      }

      $rval = SinglePage::add('/dashboard/system/modulechecker', $pkg);

    }

  }
