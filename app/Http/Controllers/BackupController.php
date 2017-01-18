<?php
/**
 * Backup Controller
 * @author Elton Jain
 *
 */
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RemoteController;
use App\Services\Repositories\BackupRepository;
use App\Models\Sites;
use App\Models\Backups;
use Request;
use Response;
use DB;
use Exception;

class BackupController extends Controller {
  protected $backupRepo;

  public function __construct(BackupRepository $backupRepo) {
    $this->backupRepo = $backupRepo;
  }

  /**
   * List of all sites to backup
   */
  public function allSites () {
    // DB::SELECT("SELECT * from digest_captions where country = 'sg' and schedule_date = ?", [date('Y-m-d')])
    $data = [
      'sites' => $this->backupRepo->getSites()->toArray()
    ];
    return view('pages.allSites')->with('data', $data);
  }


  /**
   * View a specific site
   */
  public function viewSite ($siteId = null) {
    if(!$siteId) {
    }
    $data = $this->backupRepo->getSite($siteId)->toArray();
    return view('pages.viewSite')->with('data', $data);
  }

  /**
   * Add or Edit a site to backup list
   */
  public function addSite ($siteId = null) {
    $data = null;
    $row = null;

    // Just display data
    if($siteId) {
      // Check for data
      $row = $this->backupRepo->getSite($siteId);
      if(!$row) {
        return redirect()->route('add_site');
      }
    }

    // If Request data
    if(Request::isMethod('post')) {
      // First test connection
      $input = Request::input();

      $updatedData = [
        'site_name' => trim($input['site_name']),
        'ssh_address' => trim($input['ssh_address']),
        'ssh_username' => trim($input['ssh_username']),
        'ssh_password' => base64_encode(trim($input['ssh_password'])),
        'ssh_path' => trim($input['ssh_path']),
        'is_db_backup_enabled' => isset($input['db_yes']) ? 1 : 0,
        'db_host' => trim($input['db_host']),
        'db_database' => trim($input['db_database']),
        'db_username' => trim($input['db_username']),
        'db_password' => base64_encode(trim($input['db_password'])),
        'notes' => trim($input['notes']),
      ];

      if($row) { // Update
        $siteId = $row->fill($updatedData)->save();
        return redirect()->route('edit_site', ['id' => $siteId, 'saved' => 1]);
        // Show message it's updated
      } else { // Insert
        $siteId = Sites::insert($updatedData);
        // Redirect to all Sites
        return redirect()->route('all_sites');

      }
    }

    return view('pages.addSite')->with('data', $row ? $row->toArray() : null);
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


  /**
   * API: Backup site
   */
  public function backupApi($siteId = null) {
    $input = Request::input();
    $siteId = $siteId ? $siteID : (isset($input['id']) ? $input['id'] : null);
    if(!$siteId) {
      return Response::json([
        'status' => false,
        'message' => 'Invalid site:id'
      ]);
    }
    $row = $this->backupRepo->getSite($siteId)->toArray();
    $row['site_id'] = base64_decode($row['id']);
    $row['ssh_password'] = base64_decode($row['ssh_password']);
    $row['db_password'] = base64_decode($row['db_password']);
    
    $remote = new RemoteController($row);
    $backupStatus = $remote->doSiteBackup();

    if($backupStatus === true) {
      $response = [
        'status' => true,
        'message' => 'Backup was successful :)'
      ];
    } else {
      $response = [
        'status' => true,
        'message' => 'There was some problem with the backup. Please check with the administrator'
      ];
    }

    return Response::json($response);
  }



  /**
   * API: Restore a backup
   */
  public function restoreApi($backupId = null) {
    $input = Request::input();
    $backupId = $backupId ? $backupId : (isset($input['backupId']) ? $input['backupId'] : null);

    if(!$backupId) {
      return Response::json([
        'status' => false,
        'message' => 'Invalid backup:id'
      ]);
    }
    $row = $this->backupRepo->getBackupDetail($backupId, true);
    $rcdata = [
      'site_id' => $row->site->id,
      'ssh_address' => $row->site->ssh_address,
      'ssh_path' => $row->site->ssh_path,
      'ssh_username' => $row->site->ssh_username,
      'ssh_password' => base64_decode($row->site->ssh_password),
      'backup' => [
        'id' => $row->id,
        'filename' => $row->filename,
        'filepath' => $row->filepath,
      ]
    ];
    $remote = new RemoteController($rcdata);
    $restoreStatus = $remote->doSiteRestore();

    if($restoreStatus === true) {
      $response = [
        'status' => true,
        'message' => 'Backup was successfully restored'
      ];
    } else {
      $response = [
        'status' => true,
        'message' => 'There was some problem with the restore. Please check with the administrator'
      ];
    }

    return Response::json($response);
  }

  /**
   * API: Test SSH details
   */
  public function testSSH() {
    $input = Request::input();
    $remote = new RemoteController($input);
    $ok = $remote->connectSSH();
    if($ok === true) {
      $response = [
        'status' => true,
        'message' => 'Connection was successful'
      ];
    } else {
      $response = [
        'status' => false,
        'message' => 'Connection failed. Please check your inputs'
      ];
    }

    return Response::json($response);
  }

  /**
   * List of backups for a site
   */
  public function siteBackupList($siteId = null) {
    if(!$siteId) {
      return Response::json([
        'status' => false,
        'message' => 'Invalid site:id'
      ]);
    }
    $site = $this->backupRepo->getSite($siteId);
    $backups = Backups::whereSiteId($siteId)->orderBy('id','desc')->get()->toArray();

    $data = [
      'backups' => $backups,
      'site' => $site,
    ];

    return view('pages.siteBackupList')->with('data', $data);
  }


  /**
   * Backup Scheduler
   */
  public function pageScheduler () {

    // If Request data, Save new schedule
    $input = Request::input();
    if(Request::isMethod('post') && isset($input['site_id']) && isset($input['frequency'])) {
      $id = DB::table('jobs')->insert([
        'site_id' => $input['site_id'],
        'frequency' => $input['frequency'],
      ]);
      if($id) {
        return redirect()->route('jobs');
      }
    }

    $sites = $this->backupRepo->getSites()->toArray();
    $jobs = $this->backupRepo->getJobs();
    return view('pages.pageScheduler')->with('data', ['sites' => $sites, 'jobs' => $jobs]);
  }
  

}
