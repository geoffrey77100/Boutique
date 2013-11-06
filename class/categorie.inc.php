<?PHP
class Categorie {
	private $id;
	private $libelle;	
	function __construct($unId, $unLibelle){ // constructeur
		$this->id = $unId;
		$this->libelle = $unLibelle;
	}
	public function getId(){ // accesseur
		return $this->id ;
	}
	public function getLibelle(){
		return $this->libelle;
	}	
} 
?>