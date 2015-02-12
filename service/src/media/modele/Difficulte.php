<?php
	
namespace media\modele;
use \Illuminate\Database\Eloquent\Model ;

class Difficulte extends Model {
	protected $table = 'difficulte_pl';
	protected $primaryKey = 'id';
	public $timestamps=false;

	public function game(){
		return $this->hasMany('media\modele\Game', 'id_difficulte');
	}

}