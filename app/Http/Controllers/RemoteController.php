<?php
/**
 * Remote Controller
 * Description: Handle remote connection to a single site per instance
 * @author Elton Jain
 *
 * https://www.sitepoint.com/phpseclib-securely-communicating-with-remote-servers-via-php/
 */
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use phpseclib\Net\SSH2;
use phpseclib\Net\SFTP;
use App\Models\Sites;
use App\Models\Backups;
use Response;
use DB;

class RemoteController extends Controller {
  protected $ssh;
  protected $sftp;
  protected $site;

  protected $isConnectedSSH;
  protected $isConnectedSFTP;

  private $localBackupPath;


  /**
   * @param site: ssh_address, ssh_username, ssh_password, ssh_path
   */
  public function __construct(Array $site) {
    if($site) {
      $this->init($site);
    }
    $this->isConnectedSSH = false;
    $this->isConnectedSFTP = false;
  }

  public function init(Array $site) {
    $this->site = $site;
    if(! ($site['ssh_address'] && $site['ssh_username'] && $site['ssh_password'] && $site['ssh_path']) ) {
      return false;
    }
    $this->ssh = new SSH2($site['ssh_address']);
    $this->sftp = new SFTP($site['ssh_address']);
    $this->localBackupPath = base_path().'/backups/';

    // Create backup folder if doesn't exist
    if (!file_exists($this->localBackupPath)) {
      mkdir($this->localBackupPath.'site/', 0777, true);
      mkdir($this->localBackupPath.'db/', 0777, true);
    }
  }

  /**
   * Login to remote server via SSH
   */
  public function connectSSH() {
    if($this->isConnectedSSH) {
      return true;
    }
    if(!$this->site) {
      return 'ERROR: Provide site details';
    }
    if (!$this->ssh->login($this->site['ssh_username'], $this->site['ssh_password'])) {
        return false;
    }
    $this->isConnectedSSH = true;
    return true;
  }

  /**
   * Login to remote server via SFTP
   */
  public function connectSFTP() {
    if($this->isConnectedSFTP) {
      return true;
    }
    if(!$this->site) {
      return 'ERROR: Provide site details';
    }
    if (!$this->sftp->login($this->site['ssh_username'], $this->site['ssh_password'])) {
        return false;
    }
    $this->isConnectedSFTP = true;
    return true;
  }


  /**
   * Backup site
   */
  public function doSiteBackup() {
    // SSH server -> compress folder (hash(sitename)_date)
    // SFTP Server -> download compressed folder
    if(trim($this->site['ssh_path']) !== '' && isset($this->site['id'])) {
      // SSH into remote server if not already
      $this->connectSSH();
      // SFTP into remote server if not already
      $this->connectSFTP();

      $now = time();
      $remotePath = dirname($this->site['ssh_path']) . '/';
      $backupFilename = md5($this->site['ssh_address']).'_'.$now.'.tar.gz';
      $mysqlDumpName = md5($this->site['ssh_address']).'_'.$now.'.sql';
      $localSitePath = $this->localBackupPath.'site/'.$backupFilename;
      $localDBPath = $this->localBackupPath.'db/'.$mysqlDumpName;

      // $cmdCompress = "tar cvf - ".$this->site['ssh_path']." | gzip -9 - > ".$remotePath.$backupFilename.PHP_EOL;
      $cmdCompress = "tar -zcvf ".$remotePath.$backupFilename." -C ".$this->site['ssh_path']." .".PHP_EOL;
      $cmdDeleteSiteArchive = "rm -f ".$remotePath.$backupFilename.PHP_EOL;
      $cmdDeleteDBArchive = "rm -f ".$remotePath.$mysqlDumpName.PHP_EOL;
      // Compress remote directory
      
      // Download the compressed/archived file from server to local /backups folder
      $siteDone = $this->sftp->get($remotePath.$backupFilename, $localSitePath);
      // Wait 5 seconds
      sleep(5);
      // Remove archived file from remote server
      $this->ssh->exec($cmdDeleteSiteArchive);
      if($siteDone && $this->site['is_db_backup_enabled']) {
        // Export database
        $this->ssh->exec("mysqldump --user={$this->site['db_username']} --password={$this->site['db_password']} --host={$this->site['db_host']} {$this->site['db_database']} > {$remotePath}{$mysqlDumpName}");
        // Download database
        $dbDone = $this->sftp->get($remotePath.$mysqlDumpName, $localDBPath);
        $this->ssh->exec($cmdDeleteDBArchive);
      }
      
      //
      // STORE INFO IN DB
      //
      if($siteDone) { // && dbDone
        $inserData = [
          'site_id' => $this->site['id'],
          'filename' => $backupFilename,
          'filepath' => $localSitePath,
          'dbpath' => isset($dbDone) ? $localDBPath : null,
          'checksum' => file_exists($localSitePath) ? md5_file($localSitePath) : '',
        ];
        Backups::insert($inserData);
        Sites::find($this->site['id'])->update(['last_backup' => date('Y-m-d h:i:s', $now)]);
        return true;
      }
    }
    return false;
  }


  /**
   * Restore a backup
   */
  public function doSiteRestore() {
      // Steps
      // Check if archive file exists
      // SSH: Log into SSH server
      // SFTP: Upload backup file on remote server
      // SSH: Unzip the archive on remote server in the ssh_path directory
    if(isset($this->site['backup'])) {
      if(!file_exists($this->site['backup']['filepath'])) {
        $response = [
          'status' => false,
          'message' => 'Missing backup archive file',
        ];
        return false;
      }

      // Getting one folder previous to site path
      $previousRemotePath = dirname($this->site['ssh_path']).'/';
      $filename = $this->site['backup']['filename'];

      // gunzip -c 3d6fba4cf65ce03259655b55293e485c_1484573230.tar.gz | tar -xvf - -C abc/
      $cmdExtract = "tar -xvzf ".$previousRemotePath.$filename." -C ".$this->site['ssh_path'].PHP_EOL;
      $cmdDeleteArchive = "rm -f ".$previousRemotePath.$filename.PHP_EOL;
      $this->connectSFTP();
      $this->sftp->put($previousRemotePath.$filename, $this->site['backup']['filepath'], 'NET_SFTP_LOCAL_FILE');
      $this->connectSSH();
      $this->ssh->exec($cmdExtract);
      sleep(3);
      $this->ssh->exec($cmdDeleteArchive);

      return true;
    }
    return false;
  }

}
