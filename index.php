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
	<header>
		<ul class="abs-left">
			<li><a href="doc/fr/index.php">DOCUMENTATION</a></li>
			<li><a href="examples/fr/index.php">EXEMPLES</a></li>
			<span class="inline"><img src='FR_flag.jpg' height='26' /></span>
		</ul>
		<ul class="abs-right">
			<span class="inline"><img src='EN_flag.jpg' height='26' /></span>
			<li><a href="doc/en/index.php">DOCUMENTATION</a></li>
			<li><a href="examples/en/index.php">EXAMPLES</a></li>
		</ul>
	</header>
	<h1 class="center">PDO ALTITUDE</h1>
	<div class="indexLang" style="background-image: url(FR_flag.jpg);">
		<article>
			<h2>Outils simples pour manipuler des bases de données en PHP</h2>
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
			<p>
				<iframe src="https://ghbtns.com/github-btn.html?user=polosson&repo=pdo-altitude-framework&type=fork&count=true&size=large" frameborder="0" scrolling="0" width="158px" height="30px"></iframe>
				<br />
				<a href="https://github.com/polosson/pdo-altitude-framework"><b>Faites un FORK sur GitHub !</b><br />(https://github.com/polosson/pdo-altitude-framework)</a>
			</p>
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
		<article>
			<h2>Démarrage rapide par l'exemple :</h2>
			<pre>
<span class="comment">/**
 * Configuration (Les globales qui sont requises par Altitude)
 */</span>
<span class="function">define</span>(<span class="argument">"HOST"</span>, "localhost");
<span class="function">define</span>(<span class="argument">"USER"</span>, "username");
<span class="function">define</span>(<span class="argument">"PASS"</span>, "********");
<span class="function">define</span>(<span class="argument">"BASE"</span>, "database");
<span class="function">define</span>(<span class="argument">"DSN"</span>,  "mysql:dbname=".<span class="var">BASE</span>.";host=".<span class="var">HOST</span>);
<span class="function">define</span>(<span class="argument">"FOREIGNKEYS_PREFIX"</span>, <span class="argument">"FK_"</span>);
<span class="var">$RELATIONS</span> = <span class="operator">Array</span>(
    <span class="argument">"FK_user_ID"</span>	=> <span class="operator">Array</span>('table' => <span class="argument">"users"</span>,	'alias' => <span class="argument">"user"</span>),
    <span class="argument">"FK_item_ID"</span>	=> <span class="operator">Array</span>('table' => <span class="argument">"items"</span>,	'alias' => <span class="argument">"item"</span>),
    <span class="argument">"FK_comment_ID"</span>	=> <span class="operator">Array</span>('table' => <span class="argument">"comments"</span>,	'alias' => <span class="argument">"comment"</span>)
);

<span class="comment">/**
 * Inclusion des fichiers de classes
 */</span>
<span class="function">require</span>(<span class="argument">"classes/Listing.class.php"</span>);
<span class="function">require</span>(<span class="argument">"classes/Infos.class.php"</span>);

<span class="comment">/**
 * Exemple d'utilisation de l'objet 'Listing'
 */</span>
<span class="var">$l</span> = <span class="operator">new</span> <span class="function">Listing</span>();
<span class="var">$users</span> = <span class="var">$l</span><span class="operator">-></span><span class="function">getListe</span>(<span class="argument">"users"</span>, <span class="argument">"*"</span>, <span class="argument">"age"</span>, <span class="argument">"DESC"</span>, <span class="argument">"alive"</span>, <span class="argument">"="</span>, <span class="argument">"1"</span>);
<span class="comment">// ^ Ceci donnera un tableau contenant tout les utilisateurs de la base de
// données qui sont vivants, triés selon leur âge, du plus vieux au plus jeune.</span>

<span class="comment">/**
 * Exemple d'utilisation de l'objet 'Infos'
 */</span>
<span class="var">$i</span> = <span class="operator">new</span> <span class="function">Infos</span>(<span class="argument">"users"</span>);
<span class="var">$i</span><span class="operator">-></span><span class="function">loadInfos</span>(<span class="argument">"id"</span>, <span class="argument">"33"</span>);
<span class="var">$user</span> = <span class="var">$i</span><span class="operator">-></span><span class="function">getInfos</span>();
<span class="comment">// ^ Ceci donnera un tableau contenant toutes les informations de l'utilisateur #32.</span>
<span class="var">$i</span><span class="operator">-></span><span class="function">setInfo</span>(<span class="argument">"pseudo"</span>, <span class="argument">"Marcel"</span>);
<span class="var">$i</span><span class="operator">-></span><span class="function">save</span>();
<span class="comment">// ^ Ceci modifiera dans la BDD le pseudo de l'utilisateur précédemment chargé (#32).</span>
<span class="var">$i</span><span class="operator">-></span><span class="function">delete</span>();
<span class="comment">// ^ Ceci supprimera de la BDD l'utilisateur précédemment chargé (#32).</span>
			</pre>
		</article>
	</div><div class="indexLang" style="background-image: url(EN_flag.jpg);">
		<article>
			<h2>Simple tools for database manipulation in PHP.</h2>
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
			<p>
				<iframe src="https://ghbtns.com/github-btn.html?user=polosson&repo=pdo-altitude-framework&type=fork&count=true&size=large" frameborder="0" scrolling="0" width="158px" height="30px"></iframe>
				<br />
				<a href="https://github.com/polosson/pdo-altitude-framework"><b>FORK ME ON GitHub!</b><br />(https://github.com/polosson/pdo-altitude-framework)</a>
			</p>
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
		<article>
			<h2>Quick start by an example:</h2>
			<pre>
<span class="comment">/**
 * Configuration (Globals that are required by Altitude)
 */</span>
<span class="function">define</span>(<span class="argument">"HOST"</span>, "localhost");
<span class="function">define</span>(<span class="argument">"USER"</span>, "username");
<span class="function">define</span>(<span class="argument">"PASS"</span>, "********");
<span class="function">define</span>(<span class="argument">"BASE"</span>, "database");
<span class="function">define</span>(<span class="argument">"DSN"</span>,  "mysql:dbname=".<span class="var">BASE</span>.";host=".<span class="var">HOST</span>);
<span class="function">define</span>(<span class="argument">"FOREIGNKEYS_PREFIX"</span>, <span class="argument">"FK_"</span>);
<span class="var">$RELATIONS</span> = <span class="operator">Array</span>(
    <span class="argument">"FK_user_ID"</span>	=> <span class="operator">Array</span>('table' => <span class="argument">"users"</span>,	'alias' => <span class="argument">"user"</span>),
    <span class="argument">"FK_item_ID"</span>	=> <span class="operator">Array</span>('table' => <span class="argument">"items"</span>,	'alias' => <span class="argument">"item"</span>),
    <span class="argument">"FK_comment_ID"</span>	=> <span class="operator">Array</span>('table' => <span class="argument">"comments"</span>,	'alias' => <span class="argument">"comment"</span>)
);

<span class="comment">/**
 * Including classes files
 */</span>
<span class="function">require</span>(<span class="argument">"classes/Listing.class.php"</span>);
<span class="function">require</span>(<span class="argument">"classes/Infos.class.php"</span>);

<span class="comment">/**
 * Usage example of object 'Listing'
 */</span>
<span class="var">$l</span> = <span class="operator">new</span> <span class="function">Listing</span>();
<span class="var">$users</span> = <span class="var">$l</span><span class="operator">-></span><span class="function">getListe</span>(<span class="argument">"users"</span>, <span class="argument">"*"</span>, <span class="argument">"age"</span>, <span class="argument">"DESC"</span>, <span class="argument">"alive"</span>, <span class="argument">"="</span>, <span class="argument">"1"</span>);
<span class="comment">// ^ This will give an array with all users in database who are alive,
// sorted by age, from older to younger.</span>

<span class="comment">/**
 * Usage example of object 'Infos'
 */</span>
<span class="var">$i</span> = <span class="operator">new</span> <span class="function">Infos</span>(<span class="argument">"users"</span>);
<span class="var">$i</span><span class="operator">-></span><span class="function">loadInfos</span>(<span class="argument">"id"</span>, <span class="argument">"33"</span>);
<span class="var">$user</span> = <span class="var">$i</span><span class="operator">-></span><span class="function">getInfos</span>(<span class="argument">"*"</span>);
<span class="comment">// ^ This will give an array with all informations about user #32.</span>
<span class="var">$i</span><span class="operator">-></span><span class="function">setInfo</span>(<span class="argument">"pseudo"</span>, <span class="argument">"Marcel"</span>);
<span class="var">$i</span><span class="operator">-></span><span class="function">save</span>();
<span class="comment">// ^ This will update in database the pseudo of the previously loaded user (#32).</span>
<span class="var">$i</span><span class="operator">-></span><span class="function">delete</span>();
<span class="comment">// ^ This will remove from database the previously loaded user (#32).</span>
			</pre>
		</article>
	</div>
</body>
</html>