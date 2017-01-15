<?php
/**
 * Backup Controller
 * @author Elton Jain
 *
 * https://www.sitepoint.com/phpseclib-securely-communicating-with-remote-servers-via-php/
 */
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RemoteController;


class BackupController extends Controller {

  public function __construct() {
    $site = [
      'address' => '67.205.146.240',
      'username' => 'root',
      'pass' => 'testingxyz123',
      'path' => '/var/www/html/',
    ];

  }



  /**
   * List of all sites to backup
   */
  public function allSites () {


    return view('pages.allSites');
  }

  /**
   * View, Add or Edit a site to backup list
   */
  public function addSite ($siteId = null) {
    // add Edit flag
    return view('pages.addSite');
  }

  /**
   * Do Backup
   */
  private function doBackup ($siteId = null) {

    $this->remote->doSiteBackup();
  }

  /**
   * Backup website
   */
  private function doBackupWebsite ($siteAccess = []) {
    // Access mentioned server via SSH
    // Zip the given directory
    // Copy into this server
    $this->remote->doSiteBackup();
  }

  /**
   * Backup MySQL Database
   */
  private function doBackupDB ($dbAccess = null) {
    // Access MySQL server
    // Export entire database into sql format
    // Copy into this server
  }





}
