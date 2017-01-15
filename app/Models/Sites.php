<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Config;

class Sites extends Eloquent{

	protected $table = 'sites';
	protected $fillable = [
		'id',
		'site_name',
		'ssh_address',
		'ssh_username',
		'ssh_password',
		'ssh_path',
		'is_db_backup_enabled',
		'db_host',
		'db_username',
		'db_password',
		'db_database',
		'notes',
		'is_active',
		'created_at',
		'updated_at',
	];
}
