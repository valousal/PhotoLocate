<?php
	
namespace media\modele;
use \Illuminate\Database\Eloquent\Model ;

class Image extends Model {
	protected $table = 'image_pl';
	protected $primaryKey = 'id';
	public $timestamps=false;


	public function ville(){
		return $this->belongsTo('media\modele\Ville', 'id_ville');
	}

}