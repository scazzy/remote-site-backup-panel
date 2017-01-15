<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Config;

class Backups extends Eloquent{

	protected $table = 'backups';
	protected $fillable = [
		'site_id',
		'filename',
		'filepath',
		'checksum',
		'created_at',
		'updated_at',
	];
}
