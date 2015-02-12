<?php
	
namespace media\modele;
use \Illuminate\Database\Eloquent\Model ;

class Game extends Model {
	protected $table = 'game_pl';
	protected $primaryKey = 'id';
	public $timestamps=false;

	public function ville(){
		return $this->belongsTo('media\modele\Ville', 'id_ville');
	}

	public function difficulte(){
		return $this->belongsTo('media\modele\Difficulte', 'id_difficulte');
	}
}