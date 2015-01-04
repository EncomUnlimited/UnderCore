<?php
//@Autor: Luis Neighbur
//@Fecha: 28/12/2014

class Enigma{
	protected $server = 'http://www.underanime.net/api.php';
	protected $key = 'Dcgx38uuj2h7zvhp8NB9Ukx8';
	private $cUrl = null;
	private $error;
	private $data;
	private $uri;
	public function __construct($options = []){
		$this->cUrl = curl_init();
		$default = [
			CURLOPT_COOKIESESSION => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_USERAGENT => 'Enigma Cache Service/1.6',
			CURLOPT_HTTP200ALIASES => true,
		];
		curl_setopt_array($this->cUrl, array_merge($default,$options));
	}
	protected function sendRequest($uri){
		curl_setopt($this->cUrl, CURLOPT_URL, $uri);
		var_dump($this->cUrl);
		$this->data = curl_exec($this->cUrl);
		if(curl_errno($this->cUrl)>0){
			$error = ['nro' => curl_errno($this->cUrl), 'msj' => curl_error($this->cUrl)];
			return false;
		}
		return true;
	}
	public function getResponse(){
			return $this->data;
	}
	public function getError(){
		return $this->error;
	}
	public function __destruct(){
		curl_close($this->cUrl);
	}
}


class UnderCoreApi extends Enigma{
	private $data;
	public function getAnimeList(){
		$this->uri = "{$this->server}?key={$this->key}&get=animes";
		$this->data = $this->wwwRequest();
		return $this;
	}
	public function getChapterList($cid){
		$this->uri = "{$this->server}?key={$this->key}&get=animes";
		$this->data = $this->wwwRequest();
		return $this;
	}
	public function getCategoryList(){

	}
	public function getAnimeData($aid){

	}
	public function getAnimeUpdate($timestamp){

	}
	public function setFilter($filter){
		if($filter == STATUS_ANY || $filter == STATUS_BROADCASTING || $filter == STATUS_FINALIZED){
			$this->uri .= "&status={$filter}";
		}elseif($filter == TYPE_SERIES || $filter == TYPE_MOVIES || $filter == TYPE_OVAS){
			$this->uri .= "&type={$filter}";
		}
		return $this;
	}
	private function wwwRequest(){
		if($this->sendRequest($this->uri)){
			return $this->getResponse();
		}
	}
	public function getData(){
		return $this->data;
	}
}
define('STATUS_ANY', 0);
define('STATUS_BROADCASTING', 1);
define('STATUS_FINALIZED', 2);
define('TYPE_SERIES', 0);
define('TYPE_OVAS', 1);
define('TYPE_MOVIES', 2);
$enigma = new UnderCoreApi();
var_dump($enigma->getAnimeList()->setFilter(STATUS_BROADCASTING));
var_dump($enigma->getData());
var_dump($enigma->getError());