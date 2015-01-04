<?php
//@Autor: Luis Neighbur
//@Fecha: 28/12/2014

class Enigma{
	protected $server = 'null';
	protected $key = 'null';
	private $cUrl = null;
	private $error;
	private $data;
	private $uri;
	public function __construct(){
		$this->cUrl = curl_init();
		$this->options = [
			CURLOPT_COOKIESESSION => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_USERAGENT => 'Enigma Cache Service/1.6',
		];

	}
	protected function sendRequest($uri){
		$this->options[CURLOPT_URL] = $uri;
		curl_setopt_array($this->cUrl, $this->options);
		curl_setopt($this->cUrl, CURLOPT_URL, $uri);
		$this->data = curl_exec($this->cUrl);
		if(curl_errno($this->cUrl)>0){
			$this->error = ['nro' => curl_errno($this->cUrl), 'msj' => curl_error($this->cUrl)];
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
		return $this;
	}
	public function getChapterList($cid){
		$this->uri = "{$this->server}?key={$this->key}&get=animes";
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
		return false;
	}
	public function getData(){
		$this->data = $this->wwwRequest();
		return $this->data;
	}
	public function uri() {
		return $this->uri;
	}
}
define('STATUS_ANY', 0);
define('STATUS_BROADCASTING', 1);
define('STATUS_FINALIZED', 2);
define('TYPE_SERIES', 0);
define('TYPE_OVAS', 1);
define('TYPE_MOVIES', 2);
$enigma = new UnderCoreApi();
var_dump($enigma->getAnimeList()->setFilter(TYPE_MOVIES)->getData());