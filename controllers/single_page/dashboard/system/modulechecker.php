<?php

namespace Concrete\Package\Helper\Controller\SinglePage\Dashboard\System;
use \Concrete\Core\Page\Controller\DashboardPageController;
use Package;
use View;
use Loader;
use Log;
use Concrete\Core\Backup\ContentImporter;
use \Concrete\Core\Page\Template;
use \Concrete\Core\Page\Feed;
use \Concrete\Core\Page\Type\Type;
use \Concrete\Core\Tree\Type\Topic;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use PageType;
use Page;
use Concrete\Core\Tree\Tree;
// not to be confused with Concrete\Core\Entity\Site\Tree
use Concrete\Core\Support\Facade\Application;

use Concrete\Core\Page\Theme\Theme;
use Concrete\Core\Permission\Access\Entity\Type as AccessEntityType;
use Concrete\Core\User\Group\Group;
use Concrete\Core\Package\BrokenPackage;

class Modulecheker extends DashboardPageController
{

    public function on_start() {
      // will be run prior to any URL-based methods.
      parent::on_start();
    }

    public function on_before_render() {
      // will be run after any URL-based methods, but before the page is delivered for rendering.
      parent::on_before_render();
    }

    public function view()
    {
      $packageObjects = PackageList::getPackages();

      $data = [];

      foreach ($packageObjects as $pkg) {
        $pkgdata = Object();
          $pkgdata->handle = $pkg->getPackageHandle();
          $pkgdata->c5Version = $pkg->getPackageVersion();
          $pkgdata->path = $pkg->getPackagePath();
          $pkgdata->c5InstallDate = strtotime(getPackageInstallDate ($pkgdata->handle));
          $pkgdata->composerVersion = getComposerVersion($pkgdata->path);
          $pkgdata->gitCommitDate = getLastCommit($pkgdata->path);
          $pkgdata->error = false;

          if ($pkgdata->gitCommitDate < $pkgdata->c5InstallDate) {
            $pkgdata->error = true;
            $pkgdata->comment = 'check latest version is commited to repo'.
          }

          if ($pkgdata->c5Verion != $pkgdata->composerVersion) {
            $pkgdata->error = true;
            $pkgdata->comment = 'c5 version and composer.json version fdo not match';
          }

          if ($pkg instanceof BrokenPackage)  {
            $pkgdata->error = true;
            $pkgdata->comment = 'instance of BrokenPackage';
          }

          $data[] = $pkgdata;
      }

      $this->set('moduleData', $data);

    }

    private function getLastCommit($path) {
      $cwd = getcwd();
      chdir ($path);
      $gitResult = shell_exec('git log -1 --format=%cd');
      chdir ($cwd); // change back to starting point

      $dateTime = strtotime($gitResult);
      return $dateTime;

    }

    private function getComposerVersion($path) {
      $composerPath = $path . '/composer.json';
      if (!file_exists($composerPath)) {
        return null;
      }
      $composerDetails = json_decode(file_get_contents($composerPath));
      if ($composerDetails === false) {
        return false;
      }
      return $composerDetails->version;
    }

    private function getPackageInstallDate($handle) {

      $app = Application::getFacadeApplication();
      $db = $app->make('database')->connection();
      $r = $db->executeQuery('select pkgDateInstalled from Packages where pkgHandle = ? limit 1', array($handle));

      $row = $r->fetch();
      return $row['pkgDateInstalled']);

    }
}


}
