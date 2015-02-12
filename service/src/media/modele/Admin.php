<?php
	
namespace media\modele;
use \Illuminate\Database\Eloquent\Model ;

class Admin extends Model {
	protected $table = 'admin_pl';
	protected $primaryKey = 'id';
	public $timestamps=false;

}