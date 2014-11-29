<?
class Cat0
{
    var $_id;
	var $_nome;
	var $_posizione;
	
    function getId(){
        return $this->_id;
    }
    function setId($id){
        $this->_id = $id;
    }
    
    function getNome(){
        return $this->_nome;
    }
    function setNome($nome){
        $this->_nome = $nome;
    }
	
	function getPosizione(){
        return $this->_posizione;
    }
    function setPosizione($posizione){
        $this->_posizione = $posizione;
    }		
}
?>