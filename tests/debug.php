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
	<section>
		<h2>Listing</h2>
		<pre><?php
		try {
			$l = new Listing();
			$users = $l->getListe("users", "*", "age", "desc", "age", ">=", 32);
			print_r($users);
		}
		catch(Exception $e) {
			echo '<span class="red"><b>'.$e->getMessage().'</b></span>';
		}
		?></pre>
	</section>
	<hr>
	<section>
		<h2>Infos</h2>
		<pre><?php
		try {
			$i = new Infos("users");
			$newInfos = Array("name"=>"Alex1", "pseudo"=>"AK1", "age"=>29);
			$i->setAllInfos($newInfos);
			$user = $i->getInfos();
			print_r($user);
		}
		catch(Exception $e) {
			echo '<span class="red"><b>'.$e->getMessage().'</b></span>';
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