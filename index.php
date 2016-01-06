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

$vcheck = (version_compare(PHP_VERSION, '5.4.0') >= 0);
$pdocheck = (extension_loaded('pdo'));

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="author" content="Polosson">
	<title>PDO ALTITUDE</title>
	<link href="main.css" rel="stylesheet">
</head>
<body>
	<h1 class="center">PDO ALTITUDE</h1>
	<div class="indexLang" style="background-image: url(EN_flag.jpg);">
		<header>
			<h3>Simple, hi-level abstraction layer for databases manipulation in PHP.</h3>
		</header>
		<article>
			<p>
				Altitude provides two PHP objects allowing to perform common operations on MySQL databases, in a secure way,
				without having to write SQL requests by hand. These two objects are:
			</p>
			<ul>
				<li>
					<b>Object <code>Listing</code> :</b> To retreive a list of data from an SQL table, according to a filtering.
				</li>
				<li>
					<b>Object <code>Infos</code> :</b> For "CRUD" operations (Create Read Update Delete) on a single entry of an SQL table.
				</li>
			</ul>
		</article>
		<article>
			<h2>System checks</h2>
			<ul>
				<li class="green">Server:<br />
					<b><?php echo $_SERVER['SERVER_SIGNATURE']; ?></b>
				</li>
				<li class="<?php echo ($vcheck) ? 'green' : 'red'; ?>">PHP version:<br />
					<b>
						<?php echo phpversion(); ?>
						<br /><?php echo ($vcheck) ? 'Version OK!' : 'Bad version. We need 5.4.0 to go.'; ?>
					</b>
				</li>
				<li class="<?php echo ($pdocheck) ? 'green' : 'red'; ?>">PDO extension:<br />
					<b>
						<?php echo ($pdocheck) ? 'PDO extension available!' : 'PDO extension not available. Please enable it in your php.ini file.'; ?>
					</b>
				</li>
			</ul>
		</article>
		<header>
			<h2>Docs & Examples</h2>
			<ul>
				<li><a href="doc/en/index.php">OPEN DOCUMENTATION</a></li>
				<li><a href="examples/en/index.php">GO TO EXAMPLES</a></li>
			</ul>
		</header>
	</div><div class="indexLang" style="background-image: url(FR_flag.jpg);">
		<header>
			<h3>Simple couche d'abstraction haute pour manipuler des bases de données en PHP</h3>
		</header>
		<article>
			<p>
				Altitude fournit deux objets PHP permettant d'effectuer des opérations courantes de manière sécurisée
				sur des bases de données MySQL, sans avoir à écrire les requêtes SQL. Ces deux objets sont les suivants :
			</p>
			<ul>
				<li>
					<b>L'objet <code>Listing</code> :</b> Pour récupérer une liste de données d'une table SQL selon un filtrage.
				</li>
				<li>
					<b>L'objet <code>Infos</code> :</b> Pour les opération "CRUD" (Créer Récupérer Modifier Supprimer) sur une entrée de table SQL.
				</li>
			</ul>
		</article>
		<article>
			<h2>Vérifications système</h2>
			<ul>
				<li class="green">Serveur :<br />
					<b><?php echo $_SERVER['SERVER_SIGNATURE']; ?></b>
				</li>
				<li class="<?php echo ($vcheck) ? 'green' : 'red'; ?>">Version PHP :<br />
					<b>
						<?php echo phpversion(); ?>
						<br /><?php echo ($vcheck) ? 'Version OK !' : 'Mauvaise version. Il faut la 5.4.0 minimum pour que ça fonctionne.'; ?>
					</b>
				</li>
				<li class="<?php echo ($pdocheck) ? 'green' : 'red'; ?>">Extension PDO :<br />
					<b>
						<?php echo ($pdocheck) ? 'Extension PDO disponible !' : 'Extension PDO non disponible. Merci de l\'activer dans votre fichier php.ini.'; ?>
					</b>
				</li>
			</ul>
		</article>
		<header>
			<h2>Docs & Exemples</h2>
			<ul>
				<li><a href="doc/fr/index.php">OUVRIR LA DOCUMENTATION</a></li>
				<li><a href="examples/fr/index.php">VOIR LES EXEMPLES</a></li>
			</ul>
		</header>
	</div>
</body>
</html>