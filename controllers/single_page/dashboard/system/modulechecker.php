<?php

namespace Concrete\Package\Helper\Controller\SinglePage\Dashboard\System;
use \Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Package\PackageList;
use Package;
use View;
use Loader;
use Log;
use PageType;
use Page;
use Concrete\Core\Support\Facade\Application;

use Concrete\Core\Package\BrokenPackage;

class Modulechecker extends DashboardPageController
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


      $pkgHandles = $this->getAllPackageHandles();

      $data = [];

      foreach ($pkgHandles as $pkgHandle) {
          $pkg = Package::getByHandle($pkgHandle);
          $pkgdata = new \stdClass();
          $pkgdata->handle = $pkg->getPackageHandle();
          $pkgdata->c5Version = $pkg->getPackageVersion();
          $pkgdata->path = $pkg->getPackagePath();
          $pkgdata->c5InstallDate = strtotime($this->getPackageInstallDate ($pkgdata->handle));
          $pkgdata->composerVersion = $this->getComposerVersion($pkgdata->path);
          $pkgdata->gitCommitDate = $this->getLastCommit($pkgdata->path);
          $pkgdata->error = false;
          $pkgdata->comment = '';

          if ($pkgdata->composerVersion === null) {
            $pkgdata->comment .= '<li>no composer file present</li>';
          }

          if ($pkgdata->composerVersion === false) {
            $pkgdata->comment .= '<li>could not get data from composer file</li>';
            $pkgdata->error = true;
          }

          if ($pkgdata->gitCommitDate < $pkgdata->c5InstallDate) {
            $pkgdata->error = true;
            $pkgdata->comment .= '<li>check latest version is commited to repo</li>';
          }

          if ($pkgdata->composerVersion &&
            trim($pkgdata->c5Version) != trim($pkgdata->composerVersion)) {
            $pkgdata->error = true;
            $pkgdata->comment .= '<li>c5 version and composer.json version do not match</li>';
          }

          if ($pkg instanceof BrokenPackage)  {
            $pkgdata->error = true;
            $pkgdata->comment .= '<li>instance of BrokenPackage</li>';
          }

          $data[] = $pkgdata;
      }


      $this->set('moduleData', $data);

    }

    private function hasGitRepo($path) {
      return file_exists($path . '/.git');

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
      return ($row['pkgDateInstalled']);

    }

    private function getAllPackageHandles() {

      $app = Application::getFacadeApplication();
      $db = $app->make('database')->connection();
      $r = $db->executeQuery('select pkgHandle from Packages');

      $handles = [];
      while ($row = $r->fetch()) {
        $handles[]  = $row['pkgHandle'];
      }
      return $handles;

    }
}
