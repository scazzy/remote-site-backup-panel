<?php
/**
 * Backup Repository
 * @author Elton Jain
 *
 */
namespace App\Services\Repositories;
use App\Models\Sites;
use App\Models\Backups;

class BackupRepository extends Repository {
  public function __construct() {

  }

  /**
   * Get all Sites
   * @type {Array}
   */
  public function getSites($page = 0) {
    $sites = Sites::get();
    return $sites;
  }

  /**
   * Get single Site
   * @type {Array}
   */
  public function getSite($siteId = null, $detail = false) {
    $site = Sites::find($siteId);
    return $site ? $site : false;
  }
}
