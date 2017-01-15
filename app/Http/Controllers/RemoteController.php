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
use phpseclib\Crypt\RSA;
use phpseclib\Net\SSH2;
use phpseclib\Net\SFTP;
use App\Models\Sites;
use App\Models\Backups;
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
      mkdir($this->localBackupPath, 0777, true);
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
    if(trim($this->site['ssh_path']) !== '' && isset($this->site['site_id'])) {

      // SSH into remote server if not already
      // $this->connectSSH();

      $remotePath = rtrim($this->site['ssh_path'], '/') . '/';
      $backupFilename = md5($this->site['ssh_address']).'_'.time().'.tar.gz';

      $cmdCompress = "tar cvf - ".$this->site['ssh_path']." | gzip -9 - > ".$remotePath.$backupFilename.PHP_EOL;
      $cmdDeleteArchive = "rm -f ".$remotePath.$backupFilename.PHP_EOL;

      // Compress remote directory
      $this->ssh->exec($cmdCompress);
      // SFTP into remote server if not already
      $this->connectSFTP();
      // Download the compressed/archived file from server to local /backups folder
      $this->sftp->get($remotePath.$backupFilename, $this->localBackupPath.$backupFilename);
      // Wait 5 seconds
      sleep(5);
      // Remove archived file from remote server
      $this->ssh->exec($cmdDeleteArchive);

      //
      // STORE INFO IN DB
      //
      $inserData = [
        'site_id' => $this->site['site_id'],
        'filename' => $backupFilename,
        'filepath' => $this->localBackupPath.$backupFilename,
        'checksum' => file_exists($this->localBackupPath.$backupFilename) ? md5_file($this->localBackupPath.$backupFilename) : '',
      ];
      Backups::insert($inserData);
      Sites::find($this->site['site_id'])->fill(['last_backup' => date('Y-m-d h:i:s')])->save();

      return true;
    }
    return false;
  }

}
