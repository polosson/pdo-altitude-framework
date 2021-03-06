<?php
/**
	Copyright (C) 2015 Paul MAILLARDET - Polosson

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU Affero General Public License as
	published by the Free Software Foundation, either version 3 of the
	License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU Affero General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * SQL TABLE LISTING abstraction layer, with advanced filters
 */
class Listing {
	/**
	 * @var STRING Résultat de la chaine compilée par getList() au moment de la requête SQL
	 */
	public $request;
	/**
	 * @var ARRAY Résultat des entrées retournées par getList()
	 */
	public $result;
	/**
	 * @var OBJECT Instance PDO pour la connexion SQL
	 */
	protected $pdo;
	protected $pdoDriver;
	/**
	 * @var STRING Nom de la table courante
	 */
	protected $table;

	private $cols;
	private $sortBy;
	private $order;
	private $filter_key;
	private $filter_val;
	private $lastFilterLogic;
	private $isFiltred = false;
	private $filters = Array();
	private $filterSQL;

	/**
	 * LISTING de table SQL
	 * @param OBJECT $pdoInstance Une instance de PDO préinitialisée (optionnel)
	 */
	public function __construct ($pdoInstance=false) {
		if (!$pdoInstance || !is_object($pdoInstance))
			$this->initPDO();
		else
			$this->pdo = $pdoInstance;
	}
	public function __destruct () {
		$this->pdo = null;
	}

	/**
	 * Initialise une liste de données à récupérer pour une table donnée
	 * @param STRING $table Le nom de la table
	 * @param STRING $want Une liste de colonnes à retourner (default '*' (tout))
	 * @param STRING $sortBy La colonne à utiliser pour le tri (default 'id')
	 * @param STRING $order La direction du tri (default 'ASC')
	 * @param STRING $filter_key La colonne à utiliser pour filtrer les résultats (default FALSE (pas de filter))
	 * @param STRING $filter_comp La comparaison à effectuer pour le filtrage (default '=')
	 * @param STRING $filter_val La valeur à utiliser pour filtrer les résultats (default null)
	 * @param INT $limit Nombre maximum de données à retourner (default FALSE (pas de limite)
	 * @param BOOLEAN $withFK TRUE pour récupérer les données JOINTES (cf config->table relations) (default TRUE)
	 * @param BOOLEAN $decodeJson TRUE pour décoder les champs contenant du JSON automatiquement. FALSE pour avoir les champs JSON au format STRING (default TRUE)
	 * @param BOOLEAN $parseDatesJS TRUE pour formater les dates au format ISO 8601 pour javascript (default TRUE)
	 * @return ARRAY Le tableau des résultats, ou FALSE si aucune donnée
	 */
	public function getList ($table, $want='*', $sortBy='id', $order='ASC', $filter_key=false, $filter_comp='=', $filter_val=null, $limit=false, $withFK=true, $decodeJson=true, $parseDatesJS=true) {
		global $DATE_FIELDS;
		$this->result = Array();
		$this->table = $table;
		$this->cols  = $want;
		$this->sortBy	 = $sortBy;
		$this->order = $order;
		// Check si table existe
		if (!$this->check_table_exists($table))
			throw new Exception("Listing::getList() : Table '$table' doesn't exists");
		// pour chaque filtre défini par Listing::addFilter()
		if (is_array($this->filters) && count($this->filters) > 0) {
			$FM = '' ;
			foreach ($this->filters as $f) $FM .= $f;
			$filtrage_multiple = trim($FM, " $this->lastFilterLogic ");
		}
		if ($filter_key && (string)$filter_val != null ) {
			if (Listing::check_col_exists($filter_key)) {
				$this->isFiltred  = true;
				$this->filter_key = $filter_key;
				$this->filter_val = addslashes($filter_val);
			}
			else return false ;
		}
		if ($this->isFiltred)
			$this->request = "SELECT $this->cols FROM `$this->table` WHERE `$this->filter_key` $filter_comp '$this->filter_val' ORDER BY `$sortBy` $order";
		elseif (isset($filtrage_multiple))
			$this->request = "SELECT $this->cols FROM `$this->table` WHERE $filtrage_multiple ORDER BY `$sortBy` $order";
		elseif (isset($this->filterSQL))
			$this->request = "SELECT $this->cols FROM `$this->table` WHERE $this->filterSQL ORDER BY `$sortBy` $order";
		else
			$this->request = "SELECT $this->cols FROM `$this->table` ORDER BY `$this->sortBy` $this->order";
		if (is_int($limit))
			$this->request .= " LIMIT $limit";
		$q = $this->pdo->prepare($this->request) ;
		$q->execute();
		$result = $q->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) >= 1) {							// Formatage des résultats de la requête
			$retour = array();
			$i = 0;
			foreach ($result as $resultOK) {
				unset($resultOK['password']);
				foreach ($resultOK as $k => $v) {					// Décodage JSON le cas échéant
					if ($decodeJson && is_string($v) && preg_match('/((^\[)*(]$))|((^\{")*(}$))/', $v)) {
						$valArr = json_decode($v, true);
						if (!is_array($valArr)) continue;
						$resultOK[$k] = $valArr;
					}												// Remplacement par la Foreign Key le cas échéant (jointures)
					if ($withFK && defined('FOREIGNKEYS_PREFIX') && preg_match("/^".FOREIGNKEYS_PREFIX."/", $k)) {
						$fk = $this->getForeignKey($k, $v, $decodeJson, $parseDatesJS);
						if (!is_array($fk)) continue;
						$resultOK[$fk[0]] = $fk[1];
					}
					if (is_array(@$DATE_FIELDS) && $parseDatesJS) {
						if (in_array($k, $DATE_FIELDS))
							$resultOK[$k] = date("c", strtotime($v));	// Formatage de la date au format ISO 8601 (pour que JS puisse la parser)
					}
				}
				if (count($resultOK) == 1)							// Si une seule valeur demandée, pas besoin d'une dimension en plus
					$retour[$i] = reset($resultOK);
				else
					$retour[$i] = $resultOK;
				$i++;
			}
			$this->result = $retour ;
			return $retour;
		}
		else return false;
	}
	/**
	 * Retourne le nombre d'entrées trouvées
	 */
	public function countResults () {
		if (!$this->result)
			return 0;
		return count($this->result);
	}

	/**
	 * Ajoute une condition au filtre pour la requête
	 * @param STRING $filter_key Le nom du champ pour le filtre
	 * @param STRING $filter_comp La comparaison à utiliser pour le filtre (default "=")
	 * @param STRING $filter_val La valeur à comparer
	 * @param STRING $logic Le type de logique à utiliser avec les éventuels précédents filtres (default "AND")
	 */
	public function addFilter($filter_key=false, $filter_comp='=', $filter_val=false , $logic='AND'){
		if (!$filter_key) throw new Exception("Listing::addFilter() : Missing column name for filter");
		if (!$filter_val) throw new Exception("Listing::addFilter() : Missing value for filter search");
		$filter_val = addslashes($filter_val);
		$this->filters[] = " (`$filter_key` $filter_comp '$filter_val') $logic " ;
		$this->lastFilterLogic = $logic;
	}
	/**
	 * Ajoute une condition au filtre pour la requête, en mode "moins sécurisé", afin de permettre les fonctions SQL à la place d'une string pour $filter_val.
	 * @param STRING $filter_key Le nom du champ pour le filtre
	 * @param STRING $filter_comp La comparaison à utiliser pour le filtre (default "=")
	 * @param STRING $filter_val La valeur à comparer
	 * @param STRING $logic Le type de logique à utiliser avec les éventuels précédents filters (default "AND")
	 */
	public function addFilterRaw($filter_key=false, $filter_comp='=', $filter_val=false , $logic='AND'){
		if (!$filter_key) throw new Exception("Listing::addFilterRaw() : Missing column name for filter");
		if (!$filter_val) throw new Exception("Listing::addFilterRaw() : Missing value for filter search");
		$filter_val = addslashes($filter_val);
		$this->filters[] = " (`$filter_key` $filter_comp $filter_val) $logic " ;
		$this->lastFilterLogic = $logic;
	}
	/**
	 * Réinitialise le filtrage (pour effectuer une nouvelle requête, par ex.)
	 */
	public function resetFilter() {
		$this->isFiltred  = false;
		$this->filter_key = false;
		$this->filter_val = null;
		$this->filters	  = Array();
		$this->lastFilterLogic = null;
	}
	/**
	 * Défini un filtre manuel en SQL
	 * @param STRING $filter Le filtre SQL (ex. "`id` >= 30 AND `date` <= NOW()")
	 */
	public function setFilterSQL( $filter ){
		$this->filterSQL = $filter ;
	}


	/**
	 * Renvoie un tableau où l'index est $wantedIndex au lieu de 0,1,2,3,...
	 * @param STRING $wantedIndex Le nom du champ à utiliser comme index
	 * @return ARRAY Le nouveau tableau avec l'index remplacé, FALSE si erreur
	 */
	public function reindexList ($wantedIndex='id') {
		if ($this->result == null || empty ($this->result)) return false ;
		if (!Infos::colIndex_isUnique($this->table, $wantedIndex))
			throw new Exception("Listing::reindexList() : '$wantedIndex' is not an unique index for table '$this->table'");
		$newTableau = $this->result;
		Listing::array_reindex_by($newTableau, $wantedIndex);
		return $newTableau;
	}

	///////////////////////////////////////////////////////////// METHODES PRIVÉES /////////////////////////////////////////////////////

	/**
	 * Initialisation de l'objet PDO si pas encore en mémoire
	 */
	protected function initPDO () {
		$this->pdo = null;
		$this->pdo = Listing::newPDO();
		$this->pdoDriver = $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
	}
	/**
	 * Vérifie si une table existe dans la base de données
	 * @param STRING $table Le nom de la table
	 * @return BOOLEAN True si la table existe
	 */
	protected function check_table_exists ($table) {
		if ($this->pdoDriver === 'sqlite')
			$q = $this->pdo->prepare("SELECT `name` FROM `sqlite_master` WHERE `type`='table' AND `name`='$table'");
		else
			$q = $this->pdo->prepare("SHOW TABLES LIKE '$table'");
		$q->execute();
		$result = $q->fetchAll();
		return (count(@$result[0]) >= 1);
	}
	/**
	 * Vérifie si un champ existe dans la table actuelle
	 * @param STRING $column Le nom du champ
	 * @return BOOLEAN
	 */
	protected function check_col_exists ($column) {
		$q = $this->pdo->prepare("SELECT `$column` FROM `$this->table`");
		$q->execute();
		$result = $q->fetchAll();
		return (count(@$result[0]) >= 1);
	}

	/**
	 *
	 * @param STRING $k Le nom de la clé dont on veut la jointure (origine)
	 * @param INT $v La valeur à rechercher (ID de la destination)
	 * @param BOOLEAN $decodeJson TRUE pour décoder les champs contenant du JSON automatiquement. FALSE pour avoir les champs JSON au format STRING (default TRUE)
	 * @param BOOLEAN $parseDatesJS TRUE pour formater les dates au format ISO 8601 pour javascript (default TRUE)
	 * @return ARRAY Une paire (clé, valeur) de la jointure trouvée, FALSE si aucune jointure trouvée
	 */
	protected function getForeignKey ($k, $v, $decodeJson=true, $parseDatesJS=true) {
		global $RELATIONS;
		global $DATE_FIELDS;
		if (!defined('FOREIGNKEYS_PREFIX'))
			return false;
		if (!isset($RELATIONS))
			return false;
		if ($v == "")
			return false;
		$rel = $RELATIONS[$k];
		$sqlReq = "SELECT * FROM `".$rel['table']."` WHERE";
		$vArr = json_decode($v);
		if (is_array($vArr)) {
			if (count($vArr) > 0) {
				foreach($vArr as $val)
					$sqlReq .= " `id` = $val OR";
				$sqlReq = trim($sqlReq, ' OR');
			}
			else
				return false;
		}
		else
			$sqlReq .= " `id` = $v";
		$q = $this->pdo->prepare($sqlReq) ;
		$q->execute();
		if (is_array($vArr) && count($vArr) > 0)
			$retour = $q->fetchAll(PDO::FETCH_ASSOC);
		else
			$retour = $q->fetch(PDO::FETCH_ASSOC);
		if (!is_array($retour))
			return false;
		if (is_array($retour) && count($retour) == 0)
			return false;
		$resultOK = $retour;
		foreach($retour as $i => $entry) {
			if (!is_array($entry)) continue;
			foreach($entry as $kb => $vb) {
				if ($decodeJson && is_string($vb) && preg_match('/((^\[)*(]$))|((^\{")*(}$))/', $vb)) {
					$valArr = json_decode($vb, true);
					if (!is_array($valArr)) continue;
					$resultOK[$i][$kb] = $valArr;
				}
				if (preg_match("/^".FOREIGNKEYS_PREFIX."/", $kb)) {
					$fkb = $this->getForeignKey($kb, $vb);
					if (!is_array($fkb)) continue;
					$resultOK[$i][$fkb[0]] = $fkb[1];
				}
				if (is_array(@$DATE_FIELDS) && $parseDatesJS) {
					if (in_array($kb, $DATE_FIELDS))
						$resultOK[$i][$kb] = date("c", strtotime($vb));
				}
			}
		}
		return Array($rel['alias'], $resultOK);
	}

	///////////////////////////////////////////////////////////// METHODES STATIQUES /////////////////////////////////////////////////////

	/**
	 * Retourne un tableau contenant les noms des champs d'une table
	 * @param STRING $table Le nom de la table
	 * @return ARRAY Le tableau décrivant les champs de la table, FALSE si erreur
	 */
	public static function getCols ($table=false) {
		if (!$table) return false;
		$pdoTmp = Listing::newPDO();
		$driver = $pdoTmp->getAttribute(PDO::ATTR_DRIVER_NAME);
		$descrTable = Array();
		if ($driver === 'sqlite') {
			$q = $pdoTmp->prepare("PRAGMA table_info($table)");
			$q->execute();
			$result = $q->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as $col)
				$descrTable[] = $col['name'];
		}
		else {
			$q = $pdoTmp->prepare("DESCRIBE `$table`");
			$q->execute();
			$descrTable = $q->fetchAll(PDO::FETCH_COLUMN);
		}
		return $descrTable;
	}

	/**
	 * Fonction utilitaire statique pour récupérer la valeur maxi d'un champ
	 * @param STRING $table Le nom de la table
	 * @param STRING $column Le nom du champ
	 * @return MIXED La valeur la plus grande (string la + longue, int le + grand, date la plus récente...) ou FALSE si aucun résultat.
	 */
	public static function getMax ($table, $column){
		$pdoTmp = Listing::newPDO();
		$q = $pdoTmp->prepare("SELECT `$column` from `$table` WHERE `$column` = (SELECT MAX($column) FROM `$table`)");
		$q->execute();
		$result = $q->fetch(PDO::FETCH_ASSOC);
		if (count($result) >= 1)
			return $result[$column];
		else return false;

	}

	/**
	 * Retourne la valeur du prochain auto-incrément
	 * @param STRING $table Le nom de la table
	 * @return INT La valeur du prochain auto-incrément
	 */
	public static function getAIval ($table=false) {
		if (!$table) return false;
		$pdoTmp = Listing::newPDO();
		$driver = $pdoTmp->getAttribute(PDO::ATTR_DRIVER_NAME);
		if ($driver === 'sqlite')
			$q = $pdoTmp->prepare("SELECT MAX(id) AS Auto_increment FROM $table");
		else
			$q = $pdoTmp->prepare("SHOW TABLE STATUS LIKE '$table'");
		$q->execute();
		$result = $q->fetch(PDO::FETCH_ASSOC);
		if (count($result) >= 1) {
			$AIval = $result['Auto_increment'];
			if ($driver === 'sqlite')
				(int)$AIval+= 1;
			return (int)$AIval;
		}
		else return false;
	}

	/**
	 * Fonction utilitaire statique pour réindexer un tableau selon une colonne
	 * @param ARRAY $array Le tableau à réindexer
	 * @param STRING $colIndex Le nom de la colonne à utiliser pour l'index du tableau
	 * @param ARRAY $includeCols Les valeurs à remettre dans le tableau. Utiliser une liste (array) de noms de colonnes (default null -> toutes les colonnes)
	 * @return ARRAY Le tableau réindexé selon une colonne, ou FALSE si erreur
	 */
	public static function array_reindex_by (&$array, $colIndex='id', $includeCols=null) {
		if (!is_array($array)) return false;
		$arrOK = array();
		foreach ($array as $item) {
			if (!isset($item[$colIndex]))
				return false;
			if (isset($arrOK[$item[$colIndex]]))
				trigger_error("Listing::array_reindex_by() : Duplicate found in array for index '$colIndex'. Some data may have been overwritten", E_USER_WARNING);
			if (is_array($includeCols)) {
				if (count($includeCols) === 1) {
					if ($includeCols[0] === $colIndex)
						trigger_error("Listing::array_reindex_by() : Column index '$colIndex' is the same as wanted value. Useless call of this method");
					$arrOK[$item[$colIndex]] = $item[$includeCols[0]];
				}
				else {
					foreach($includeCols as $col) {
						if (!isset($item[$col]))
							continue;
						$arrOK[$item[$colIndex]][$col] = $item[$col];
					}
				}
			}
			else
				$arrOK[$item[$colIndex]] = $item;
		}
		$array = $arrOK;
		return $arrOK;
	}

	protected static function newPDO() {
		$pdo = new PDO(DSN, USER, PASS);
		$pdoDriver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
		if ($pdoDriver !== 'sqlite') {
			$pdo->query("SET NAMES 'utf8'");
			$pdo->setAttribute(PDO::ATTR_PERSISTENT, true);
		}
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $pdo;
	}
}
