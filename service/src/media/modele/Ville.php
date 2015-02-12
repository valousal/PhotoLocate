<?php
	
namespace media\modele;
use \Illuminate\Database\Eloquent\Model ;

class Ville extends Model {
	protected $table = 'ville_pl';
	protected $primaryKey = 'id';
	public $timestamps=false;

	public function images(){
		return $this->hasMany('media\modele\Image', 'id_ville');
	}

	public function game(){
		return $this->hasMany('media\modele\Game', 'id_ville');
	}
}