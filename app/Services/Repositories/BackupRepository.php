<?php
/**
 * Backup Repository
 * @author Elton Jain
 *
 */
namespace App\Services\Repositories;
use App\Models\Sites;
use App\Models\Backups;
use DB;

class BackupRepository extends Repository {

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

  /**
   * Get Backup Detail
   * @type {Array}
   */
  public function getBackupDetail($backupId = null, $detail = false) {
    $data = Backups::find($backupId);
    if($detail === true) {
      $data->site = Sites::find($data->site_id);
    }
    return $data;
  }

  /**
   * Get Backup Schedules
   * @type {Array}
   */
  public function getJobs() {
    $data = DB::select("
      SELECT c.id, c.site_id, c.frequency, s.site_name, s.ssh_address
      FROM jobs c LEFT JOIN sites s ON c.site_id = s.id
      ORDER BY c.created_at DESC
    ");
    return $data;
  }


}
