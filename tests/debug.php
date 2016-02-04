<?php
/**
  Copyright (C) 2015  Polosson

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

require("SQL_config.php");
/** CONTENT OF "SQL_config.php" IS :
 * define("HOST", "localhost");
 * define("USER", "user");
 * define("PASS", "*******");
 * define("BASE", "altitude-example");
 * define("DSN",  "mysql:dbname=".BASE.";host=".HOST);
 */

define("FOREIGNKEYS_PREFIX", "FK_");
$RELATIONS = Array(
    "FK_user_ID"	=> Array('table' => "users",	'alias' => "user"),
    "FK_item_ID"	=> Array('table' => "items",	'alias' => "item"),
    "FK_comment_ID"	=> Array('table' => "comments",	'alias' => "comment")
);
define("DATE_CREATION", "date_creation");
define("LAST_UPDATE", "last_action");
$DATE_FIELDS = Array("date", "last_action", "date_creation");

require("../classes/Listing.class.php");
require("../classes/Infos.class.php");

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="author" content="Polosson">
	<title>PDO ALTITUDE - debug</title>
	<link href="../main.css" rel="stylesheet">
</head>
<body>
	<h1>ALTITUDE DEBUG PAGE</h1>
	<section  style="max-width: 50%;">
		<h2>Listing</h2>
		<?php $table = "users"; ?>
		<h4>Table "<?php echo $table ?>"</h4>
		<pre><?php
		try {
			$l = new Listing();
			$liste = $l->getList($table);
			$liste = $l->reindexList('id');
//			Listing::array_reindex_by($liste, 'ref');
			print_r($liste);
			var_dump(Infos::colExists($table, 'name'));
		}
		catch(Exception $e) {
			echo '<span class="red"><b>'.$e->getMessage().'</b></span><br />';
			echo $e->getTraceAsString();
		}
		?></pre>
	</section>
	<section style="max-width: 50%;">
		<h2>Infos</h2>
		<h4>Table "users", action : update entry</h4>
		<pre><?php
		try {
//			$i = new Infos("users");
//			$i->loadInfos('id', 6);
//			$newInfos = Array("name"=>"Alex", "pseudo"=>"AKtsuki", "age"=>29 );
//			$i->setManyInfos($newInfos);
//			$i->save();
//			$user = $i->getManyInfos();

//			var_dump(Infos::addNewCol('users', 'testN2', 'INT(3)', 254));
		}
		catch(Exception $e) {
			echo '<span class="red"><b>'.$e->getMessage().'</b></span><br />';
			echo $e->getTraceAsString();
		}
		?></pre>
	</section>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
</body>
</html>