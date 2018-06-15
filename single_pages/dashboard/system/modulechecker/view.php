<!--
There is only one template file for the single pagecontroller
Any call to different methods e.g. edit(), add() from the Controller
all end up rendering here.

see https://www.concrete5.org/community/forums/customizing_c5/single-pages-and-controller-methods
-->

<?php

defined('C5_EXECUTE') or die('Access Denied.');


// $view is of tpye Concrete\Core\Page\View\PageView
?>

<table id = "packages">
  <tr>
    <th>Installed Package handle</th>
    <th>C5 package version</th>
    <th>Composer version</th>
    <th>Package install time</th>
    <th>Latest git commit</th>
    <th>Comment</th>
  </tr>
<?php
  if (is_array($moduleData)) {
    foreach ($moduleData as $row) {
      if ($row->error) {
        echo '<tr style = "background-color:PaleVioletRed;">';
      }
      else {
        echo '<tr>';
      }

      print '<td>' . $row->handle . '</td>';
      print '<td>' . $row->c5Version . '</td>';
      print '<td>' . $row->composerVersion . '</td>';
      print '<td>' . date('D/m/Y H:i:s', $row->c5InstallDate). '</td>';
      print '<td>' . date('D/m/Y H:i:s', $row->gitCommitDate). '</td>';
      print '<td><ul>' . $row->comment . '</ul></td>';


      echo ('</tr>');
    }
  }
?>

</table>
