<?php

namespace Concrete\Package\Helper; //<--must match package name
use Concrete\Core\Page\Single as SinglePage;
use \Concrete\Core\Package\Package;
use Page;
use Config;
use Concrete\Core\Database\EntityManager\Provider\ProviderAggregateInterface;
use Concrete\Core\Database\EntityManager\Provider\StandardPackageProvider;

//  implement ProviderAggregateInterface when ORM enitites are provded by
// package

class Controller extends Package implements ProviderAggregateInterface
{

    protected $pkgHandle = 'helper'; //<--must match package name
    protected $appVersionRequired = '8.3.2';
    protected $pkgVersion = '0.6.14';


    // see https://documentation.concrete5.org/developers/packages/adding-custom-code-to-packages
    protected $pkgAutoloaderRegistries = array(
        'src/Helper/Permissions/' => '\Helper\Permissions',
        'src/Helper/ComposerJson/' => '\Helper\ComposerJson',
        'src/Helper/Block/' => '\Helper\Block',
        'src/Helper/Get/' => '\Helper\Get',
        'src/Helper/LdJson/' => '\Helper\LdJson',
        //'src/Entity/' => '\Entity',
    );

    /*used for making Entity, implementation of ProviderAggregateInterface*/
    public function getEntityManagerProvider()
    {
        $provider = new StandardPackageProvider($this->app, $this, [
            'src/Entity' => 'Concrete\Package\Helper\Entity',
            //'src/Testing/Entity' => 'PortlandLabs\Testing\Entity'
        ]);
        return $provider;
    }

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
      $rval = SinglePage::add('/dashboard/system/modulechecker', $pkg);

      $page=Page::getByPath('/dashboard/system/rich_snipetts/global_page_default');
      if ($page) {
        $page->delete();
      }
      $rval = SinglePage::add('/dashboard/system/rich_snipetts/global_page_default', $pkg);

    }

  }
