<? 
include_once "Cat0.php";

class Todo
{
    var $_id;
	var $_posizione;
	var $_testo;
	var $_data;
	var $_priorita;
	var $_idCat;
	var $_Cat0;
	
	/*
	public function __toString(){
		return $this->getId();
	}
	*/
	
	/*id*/
    function getId(){
        return $this->_id;
    }
    function setId($id){
        $this->_id = $id;
    }
    /*testo*/
    function getTesto(){
        return $this->_testo;
    }
    function setTesto($testo){
        $this->_testo = $testo;
    }
	/*posizione*/
	function getPosizione(){
		return $this->_posizione;
    }
    function setPosizione($posizione){
        $this->_posizione = $posizione;
    }
	/*data*/
	function getData(){
		return $this->_data;
    }
    function setData($data){
        $this->_data = $data;
    }
	/*priorita*/
	function getPriorita(){
		return $this->_priorita;
    }
    function setPriorita($priorita){
        $this->_priorita = $priorita;
    }
	/*id categoria*/
	function getIdCat(){
		return $this->_idCat;
    }
    function setIdCat($idCat){
        $this->_idCat = $idCat;
    }
	
	/*Cat0*/
	function getCat(){
		return $this->_Cat0;
    }
    function setCat($Cat0){
        $this->_Cat0 = $Cat0;
    }	
	
	public function __toString()
    {
        return $this->getId()." ".$this->getTesto();
    }
		
}
?>