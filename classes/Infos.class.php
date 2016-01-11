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
 * SQL ENTRY usage abstraction layer (one-entry save, update, delete from SQL table)
 */
class Infos extends Listing {

	private $data	= Array();	// Tableau des infos de l'entrée
	private $loaded = false;	// TRUE si la BDD a déjà été lue (update ou insert -> cf. méthode save()< )

	/**
	 * RÉCUPÉRATION d'entrée(s) de table SQL
	 * @param STRING $table Le nom de la table SQL
	 * @param OBJECT $pdoInstance Une instance de PDO préinitialisée (optionnel)
	 */
	public function __construct($table=false, $pdoInstance=false) {
		if ($table == false)
			throw new Exception("Infos::__construct() : missing table name");
		Listing::__construct($pdoInstance);
		$this->setTable($table);
	}
	/**
	 * Définition la table où rechercher/ajouter une entrée
	 * @param STRING $table Le nom de la table
	 */
	public function setTable ($table) {
		if (!$this->check_table_exists($table))
			throw new Exception("Infos::setTable() : Table '$table' doesn't exists");
		$this->table	= $table;
		$this->loaded	= false;
		$this->data		= array();
	}
	/**
	 * Retourne le nom de la table courante
	 * @return STRING Le nom de la table courante
	 */
	public function getTable () {
		return $this->table;
	}
	/**
	 * Charge une entrée selon un filtrage basique. Renvoie une erreur si plusieurs entrées ont été trouvées.
	 * @param STRING $filtreKey Le nom de la colonne pour le filtre
	 * @param STRING $filtreVal La valeur du champ pour le filtre
	 * @param BOOLEAN $withFK TRUE pour récupérer les données JOINTES (cf config->table relations) (default TRUE)
	 * @param BOOLEAN $decodeJson TRUE pour décoder les champs contenant du JSON automatiquement. FALSE pour avoir les champs JSON au format STRING (default TRUE)
	 * @param BOOLEAN $parseDatesJS TRUE pour formater les dates au format ISO 8601 pour javascript (default TRUE)
	 */
	public function loadInfos ($filtreKey, $filtreVal, $withFK=true, $decodeJson=true, $parseDatesJS=true) {
		$this->loaded	= false;
		$this->data		= array();
		$result = $this->getList($this->table, "*", 'id', 'ASC', $filtreKey, "=", $filtreVal, false, $withFK, $decodeJson, $parseDatesJS);
		if (!is_array($result))
			return;
		if (count($result) > 1)
			throw new Exception("Infos::loadInfos() : Several entries (".count($result).") found for '$filtreKey = $filtreVal'! Please refine your filter");
		foreach	(reset($result) as $key=>$val)
			$this->setInfo($key, $val);
		$this->loaded = true;
	}
	/**
	 * Vérifier si les infos ont bien été chargées
	 * @return BOOLEAN TRUE si les infos ont déjà été chargées depuis la BDD
	 */
	public function isLoaded () {
		return $this->loaded;
	}

	/**
	 * Récupération de la valeur d'une colonne spécifiée
	 * @param STRING $column Le nom du champ dont on veut l'info
	 * @return MIXED La valeur de l'info demandée, FALSE si aucune info trouvée
	 */
	public function getInfo ($column=false) {
		if (!$column)
			throw new Exception("Infos::getInfo() : Missing column name");
		if (!isset($this->data[$column]))
			return false;
		return $this->data[$column];
	}

	/**
	 * Récupération de plusieurs infos spécifiées, ou de toutes les infos de l'entrée en mémoire
	 * @param ARRAY|STRING $columns Le nom des colonnes dont on veut l'info, dans un tableau, ou une chaîne séparés par des virgules (default "*" -> toutes les infos)
	 * @return ARRAY Un tableau contenant les infos demandées, tableau vide si aucune info trouvée
	 */
	public function getManyInfos ($columns="*") {
		if ($columns == "*")
			return $this->data;
		$return = Array();
		if (is_string($columns))
			$columns = explode(',', $columns);
		if (is_array($columns)) {
			foreach($columns as $col) {
				if (!isset($this->data[$col])) continue;
				$return[$col] = $this->data[$col];
			}
		}
		else return false;
		return $return;
	}
	/**
	 * Compte le nombre d'infos en mémoire
	 * @return INT Le nombre d'infos (colonnes)
	 */
	public function countInfos () {
		return count($this->data);
	}

	/**
	 * Ajoute / modifie une info dans la mémoire
	 * @param STRING $key Le nom de la colonne
	 * @param STRING $val La valeur du champ
	 */
	public function setInfo ($key, $val) {
		$this->data[$key] = $val ;
	}
	/**
	 * Modifie plusieurs infos d'une entrée dans la mémoire (permet de vérifier l'intégrité de l'entrée, en terme de colonnes)
	 * @param ARRAY $newInfos Un tableau avec les nouvelles valeurs pour les colonnes de l'entrée
	 * @param BOOLEAN $allowAddCol TRUE pour ignorer les colonnes en trop, FALSE pour permettre l'ajout de colonnes non existantes (default FALSE)
	 * @param BOOLEAN $checkMissing TRUE pour vérifier qu'il ne manque pas de colonne (renvoie une erreur), FALSE pour laisser MySQL remplir les valeurs manquantes (default FALSE)
	 * @param BOOLEAN $forceID TRUE pour obliger la redéfinition de la colonne "id", FALSE pour l'ignorer et laisser MySQL faire son auto-incrément (default FALSE)
	 */
	public function setManyInfos ($newInfos, $allowAddCol=false, $checkMissing=false, $forceID=false) {
		if (!is_array($newInfos))
			throw new Exception("Infos::setManyInfos() : \$newInfos must be an array (".gettype($newInfos)." found)");
		$tableCols = Infos::getCols($this->table);
		if ($checkMissing) {
			$missingRows = array_diff($tableCols, array_keys($newInfos));
			sort($missingRows);
			if (count($missingRows) > 0)
				throw new Exception("Infos::setManyInfos() : missing ".count($missingRows)." columns in array \$newInfos, compared to current table ('$this->table'). List of missing columns: ".json_encode($missingRows));
		}
		if (!$allowAddCol) {
			$surplusRows = array_diff(array_keys($newInfos), $tableCols);
			if (count($surplusRows) > 0) {
				foreach($surplusRows as $sRow)
					unset($newInfos[$sRow]);
			}
		}
		if (!$forceID)
			unset($newInfos['id']);
		foreach($newInfos as $key => $val)
			$this->setInfo($key, $val);
	}

	/**
	 * MISE À JOUR (SAVE) d'une entrée dans la table courante
	 * @param STRING $filterKey Le nom de la colonne à utiliser pour identifier l'entrée (default 'id')
	 * @param STRING $filterVal L'identifiant à utiliser (default 'this' -> correspond à l'entrée actuelle)
	 * @param BOOLEAN $autoAddCol TRUE pour ajouter la(les) colonne(s) si elle(s) n'existe(nt) pas
	 * @param STRING $autoDate TRUE pour mettre à jour le champ de dernière modification avec la date courante, et la date de création dans le cas d'un INSERT, si la colonne est présente. (default TRUE)
	 * @return STRING Le type de requête SQL qui vient d'être utilisée pour le save ('UPDATE', ou 'INSERT')
	 */
	public function save ($filterKey='id', $filterVal='this', $autoAddCol=true, $autoDate=true) {
		if (!$this->pdo || !is_object($this->pdo))
			$this->initPDO();
		// si pas d'argument on utilise l'entrée courante
		if ($filterVal == 'this')
			$filterVal = @$this->data[$filterKey];
		// Mise à jour d'une éventuelle colonne de date de last modif (la constante de config LAST_UPDATE doit être définie)
		if ($autoDate && defined("LAST_UPDATE") && LAST_UPDATE !== false)
			$this->data[LAST_UPDATE] = date("Y-m-d H:i:s");
		// Vérifie si tous les champs existent, sinon crée le champ à la volée
		if ($autoAddCol)
			$this->createMissingCols();
		// Construction de la chaine des clés et valeurs SQL pour la requête
		$keys = ''; $vals = ''; $up = '';
		foreach ($this->data as $k => $v) {
			if (is_array($v)) $v = json_encode($v, JSON_UNESCAPED_SLASHES+JSON_UNESCAPED_UNICODE+JSON_NUMERIC_CHECK);
			if (is_string($v)) $v = addslashes($v);
			$keys .= "`$k`, " ;
			$vals .= "'$v', " ;
			$up   .= "`$k`='$v', ";
		}
		$keys = rtrim($keys, ', ');
		$vals = rtrim($vals, ', ');
		$up   = rtrim($up,   ', ');
		// Update de l'entrée si chargée en mémoire
		if ($this->loaded)
			$req = "UPDATE `$this->table` SET $up WHERE `$filterKey` LIKE '$filterVal'";
		else {	// Insertion de l'entrée si nouvelle
			$req = "INSERT INTO `$this->table` ($keys) VALUES ($vals)";
			// Ajout de la date de création (si la colonne est présente) (la constante de config DATE_CREATION doit être définie)
			if ($autoDate && defined("DATE_CREATION") && DATE_CREATION !== false)
				$this->data[DATE_CREATION] = date("Y-m-d H:i:s");
			$nextid = Listing::getAIval($this->table);
		}
		// Sauvegarde en base de données
		$q = $this->pdo->prepare($req) ;
		try { $q->execute(); }
		catch (Exception $e) {
			$msg = $e->getMessage();
			if ($e->getCode() == 23000){
				$keyOffset = strrpos($msg, "'", -2);
				$key = preg_replace("/'/", "", substr($msg, $keyOffset));
				throw new Exception("Infos::save() : Duplicate entry for `$key`=\"".$this->data[$key]."\" in table '$this->table'.");
			}
			else
				throw new Exception("Infos::save() : table '$this->table' -> $msg");
		}
		if (@$nextid) {
			$this->data['id'] = (int)$nextid;
			$this->loadInfos('id', (int)$nextid);
		}
		$this->loaded = true;
		$err = $q->errorInfo();
		if ($err[0] == 0)
			return substr($req, 0, 6); // retourne "INSERT" ou "UPDATE"
		else
			throw new Exception('Infos::save() : '.$err[2]);
	}

	/**
	 * Supprime une (ou plusieurs) entrée(s) de la base de données
	 * @param STRING $filterKey Le nom de la colonne à utiliser pour identifier l'entrée (default 'id')
	 * @param STRING $filterVal L'identifiant à utiliser (default 'this' -> correspond à l'entrée actuelle)
	 * @param STRING $filtrePlus Un flitre additionnel à ajouter à la requête SQL pour identifier l'entrée (optionnel)
	 * @return INT Le nombre de lignes supprimées
	 */
	public function delete ($filterKey='id', $filterVal='this', $filtrePlus=null) {
		if (!$this->pdo || !is_object($this->pdo))
			$this->initPDO();
		// si pas d'argument on utilise l'entrée courante
		if ($filterVal == 'this')
			$filterVal = $this->data[$filterKey];
		// construction de le requête
		$sqlReq = "DELETE FROM `$this->table` WHERE `$filterKey` = \"$filterVal\"";
		if ($filtrePlus)
			$sqlReq .= " AND ".$filtrePlus;
		// suppression de l'entrée
		$q = $this->pdo->prepare($sqlReq);
		$q->execute();
		$err = $q->errorInfo();
		if ($err[0] == 0)
			return $q->rowCount();
		else
			throw new Exception($err[2]);
	}


///////////////////////////////////////////////// METHODES PRIVÉES /////////////////////////////////////////////////

	/**
	 * Vérifie si tous les champs existent, sinon création de la colonne à la volée
	 */
	private function createMissingCols () {
		if (!$this->pdo || !is_object($this->pdo))
			$this->initPDO();
		$q = $this->pdo->prepare("SHOW COLUMNS FROM `$this->table`");
		$q->execute();
		if ($q->rowCount() == 0) return;
		$colums = $q->fetchAll();
		foreach ($this->data as $k => $v) {
			$exist = false ;
			foreach ($colums as $c => $dataC) {
				if ($k == $dataC["Field"]) {
					$exist = true;
					break;
				}
			}
			if (!$exist)
				$this->autoAddCol($k, $v);
		}
	}

	/**
	 * Ajoute une colonne à la table courante, avec choix du type automatique
	 * @param STRING $colName Le nom de la colonne
	 * @param STRING $val La valeur de la colonne (pour le check auto du type de colonne)
	 * @return BOOLEAN TRUE si succès, FALSE si échec
	 */
	private function autoAddCol ($colName, $val) {
		if (!$this->pdo || !is_object($this->pdo))
			$this->initPDO();
		if (is_array($val) || ((strpos('!', $val) !== false) && (strpos('\'', $val) !== false) && (strpos('?', $val) !== false) && (strpos('#', $val) !== false)))
			return false;
		$char = '' ;
		if (is_numeric($val)) {										// check du type de valeur du champ à ajouter
			$tailleNbre = strlen((string)$val);
			$tailleChamp = (int)$tailleNbre + 2;					// taille maxi de la valeur du champ
			if (ctype_digit($val))
				$colType = 'INT( '.$tailleChamp.' )';				// Si c'est un nombre entier
			else $colType = 'FLOAT( '.$tailleChamp.' )';			// Si c'est un nombre à virgule
		}
		elseif (is_string($val)) {
			$char = "CHARACTER SET utf8 COLLATE utf8_general_ci" ;
			if (strlen($val) <= 30)
				$colType = 'VARCHAR(256)';							// Si c'est une petite chaîne
			else $colType = 'TEXT';									// Si c'est une grande chaîne
		}
		$sqlAlter = "ALTER TABLE `$this->table` ADD `$colName` $colType $char NOT NULL" ;
		$a = $this->pdo->prepare($sqlAlter);
		return $a->execute();
	}


///////////////////////////////////////////////// METHODES STATIQUES //////////////////////////////////////////////////

	/**
	 * Check si une colonne existe dans la table courante
	 * @param STRING $table Le nom de la table
	 * @param STRING $colName Le nom de la colonne
	 * @return BOOLEAN TRUE si succès, FALSE si erreur.
	 */
	public static function colExists ($table, $colName) {
		$sqlReq = "SELECT `$colName` FROM `$table`";
		try {
			$pdoTmp = new PDO(DSN, USER, PASS, array(PDO::ATTR_PERSISTENT => true));
			$pdoTmp->query("SET NAMES 'utf8'");
			$pdoTmp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$q = $pdoTmp->prepare($sqlReq);
			$q->execute();
			return ($q->rowCount() >= 1);
		}
		catch (Exception $e) { return false; }
	}

	/**
	 * Check si une colonne a un index UNIQUE (CàD si elle peut avoir la même valur pour plusieurs entrées)
	 * @param STRING $table Le nom de la table
	 * @param STRING $colName Le nom de la colonne à vérifier
	 * @return BOOLEAN TRUE si la colonne a un index Unique, FALSE sinon
	 */
	public static function colIndex_isUnique ($table, $colName) {
		$sqlReq = "SHOW INDEXES FROM $table" ;
		try {
			$pdoTmp = new PDO(DSN, USER, PASS, array(PDO::ATTR_PERSISTENT => true));
			$pdoTmp->query("SET NAMES 'utf8'");
			$pdoTmp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$q = $pdoTmp->prepare($sqlReq);
			$q->execute();
			$result = $q->fetchAll(PDO::FETCH_ASSOC);
			$is_unique = false;
			foreach ($result as $i => $param) {
				if ($param['Column_name'] == $colName) {
					if ($param['Non_unique'] == 0) {
						$is_unique = true;
						break;
					}
				}
			}
			return $is_unique;
		}
		catch (Exception $e) { return false; }
	}

	/**
	 * Ajoute une colonne dans une table de la base de données
	 * @param STRING $table Le nom de la table
	 * @param STRING $colName Le nom de la nouvelle colonne
	 * @param STRING $colType Le type de colonne à créer (default "VARCHAR(64)")
	 * @param STRING $defaultVal La valeur par défaut pour la colonne (optionnel, et inutile pour le type "TEXT")
	 * @return BOOLEAN TRUE si succès, FALSE si erreur.
	 */
	public static function addNewCol ($table='', $colName='', $colType='VARCHAR(64)', $defaultVal="") {
		if ($table == '')
			throw new Exception("Infos::addNewCol() : Missing table name");
		if ($colName == '')
			throw new Exception("Infos::addNewCol() : Missing column name");
		if (Infos::colExists($table, $colName))
			throw new Exception("Infos::addNewCol() : This column already exists");
		$extraReq = "";
		if (preg_match('/CHAR|TEXT/i', $colType))
			$extraReq = "CHARACTER SET utf8 COLLATE utf8_general_ci ";
		$extraReq .= "NOT NULL";
		if (!preg_match('/TEXT/i', $colType))
			$extraReq .= " DEFAULT '$defaultVal'";
		$pdoTmp = new PDO(DSN, USER, PASS, array(PDO::ATTR_PERSISTENT => true));
		$pdoTmp->query("SET NAMES 'utf8'");
		$pdoTmp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sqlAlter = "ALTER TABLE `$table` ADD `$colName` $colType $extraReq" ;
		$a = $pdoTmp->prepare($sqlAlter);
		return ($a->execute());
	}

	/**
	 * Supprime une colonne d'une table de la base de données
	 * @param STRING $table Le nom de la table
	 * @param STRING $colName Le nom de la colonne
	 * @return BOOLEAN
	 */
	public static function removeCol ($table='', $colName='') {
		if ($table == '' && $colName == '') return false;
		if ($colName == 'id') return false;
		$pdoTmp = new PDO(DSN, USER, PASS, array(PDO::ATTR_PERSISTENT => true));
		$pdoTmp->query("SET NAMES 'utf8'");
		$pdoTmp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sqlReq = "ALTER TABLE `$table` DROP `$colName`";
		$q = $pdoTmp->prepare($sqlReq);
		if ($q->execute()) return true;
		else return false;
		unset($pdoTmp);
	}

}