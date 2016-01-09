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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="author" content="Polosson">
	<title>PDO ALTITUDE - EXEMPLES</title>
	<link href="../../main.css" rel="stylesheet">
</head>
<body>
	<a id="top"></a>
	<header>
		<ul class="fix-right">
			<li><a href="../../index.php">< INDEX</a></li>
			<li><a href="#top">^ Intro</a></li>
			<li><a href="#listing">Listing</a></li>
			<li><a href="#infos">Infos</a></li>
			<li><a href="#licence">Licence</a></li>
			<li><a href="../../doc/fr/index.php">DOCUMENTATION ></a></li>
		</ul>
	</header>
	<div class="menu-left">
		<h1>PDO Altitude<br />Exemples</h1>
		<hr>
		<ol>
			<li><a href="#top">Introduction</a>
				<ol>
					<li><a href="#I1">Base de données</a></li>
					<li><a href="#I2">Configuration</a></li>
				</ol>
			</li>
			<li><a href="#listing">Listing</a>
				<ol>
					<li><a href="#L1">Utilisation simple</a></li>
					<li><a href="#L2">Le Tri</a></li>
					<li><a href="#L3">Le filtrage</a></li>
					<li><a href="#L4">Les jointures</a></li>
				</ol>
			</li>
			<li><a href="#infos">Infos</a>
				<ol>
					<li><a href="#F1">Modifier une entrée</a></li>
					<li><a href="#F2">Créer une entrée</a></li>
					<li><a href="#F3">Ajout de colonnes</a></li>
				</ol>
			</li>
		</ol>
	</div>
	<div class="content">
		<section>
			<h2>Introduction</h2>
			<article>
				<a id="I1"></a>
				<h3>Base de données des exemples</h3>
				<p>Pour nos exemples, nous utiliserons une base de données contenant 3 tables SQL que voici :</p>
				<h4>Table "users"</h4>
				<table class="table-DB">
					<tr>
						<td width="20">id</td><td>name</td><td>pseudo</td><td>age</td><td>last_action</td><td>alive</td>
					</tr>
					<tr>
						<td>1</td><td>Paul</td><td>Polo</td><td>34</td><td>2016-01-14</td><td>1</td>
					</tr>
					<tr>
						<td>2</td><td>Marcel</td><td>Mamar</td><td>75</td><td>2012-04-24</td><td>0</td>
					</tr>
					<tr>
						<td>3</td><td>Jacques</td><td>Jack</td><td>22</td><td>2014-08-04</td><td>1</td>
					</tr>
					<tr>
						<td>4</td><td>Julie</td><td>Grenouille</td><td>32</td><td>2015-10-28</td><td>1</td>
					</tr>
					<tr>
						<td>5</td><td>Henri</td><td>Riton</td><td>30</td><td>2015-09-22</td><td>1</td>
					</tr>
				</table>
				<h4>Table "items"</h4>
				<table class="table-DB">
					<tr>
						<td width="20">id</td><td>ref</td><td>FK_user_ID</td><td>FK_comment_ID</td><td>date_creation</td><td>content</td>
					</tr>
					<tr>
						<td>1</td><td>itemFlask</td><td>1</td><td></td><td>2015-02-28</td><td>["a","bit","of","json"]</td>
					</tr>
					<tr>
						<td>2</td><td>itemBall</td><td>3</td><td>2</td><td>2015-07-29</td><td>{"parts":22,"type":"leather","hardness":5.314,"filled":true}</td>
					</tr>
					<tr>
						<td>3</td><td>itemFlow</td><td>4</td><td></td><td>2015-07-06</td><td>[]</td>
					</tr>
					<tr>
						<td>4</td><td>itemNiuk</td><td>3</td><td>1</td><td>2015-01-14</td><td>{"leski":"mow","gniuk":"gniuk"}</td>
					</tr>
					<tr>
						<td>5</td><td>itemZaa</td><td>5</td><td>3</td><td>2015-12-05</td><td>[7,356,20,16]</td>
					</tr>
				</table>
				<h4>Table "comments"</h4>
				<table class="table-DB">
					<tr>
						<td width="20">id</td><td>date</td><td>FK_user_ID</td><td>FK_item_ID</td><td>text</td>
					</tr>
					<tr>
						<td>1</td><td>2015-11-21</td><td>1</td><td>4</td><td>What a nice comment, gniuk gniuk!</td>
					</tr>
					<tr>
						<td>2</td><td>2015-12-04</td><td>3</td><td>2</td><td>I'm writing something about balls.</td>
					</tr>
					<tr>
						<td>3</td><td>2015-12-23</td><td>5</td><td>5</td><td>Wazzaaaaa!</td>
					</tr>
				</table>
				<a id="I2"></a>
				<h3>Configuration pour les exemples</h3>
				<p>Avant d'utiliser les outils, il faut définir les constantes et les variables de configuration. Nous utiliserons donc la configuration suivante :</p>
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
<span class="var">$DATE_FIELDS</span> = <span class="operator">Array</span>(<span class="argument">"date"</span>, <span class="argument">"last_action"</span>, <span class="argument">"date_creation"</span>);

<span class="comment">/**
 * Inclusion des fichiers de classes
 */</span>
<span class="function">require</span>(<span class="argument">"classes/Listing.class.php"</span>);
<span class="function">require</span>(<span class="argument">"classes/Infos.class.php"</span>);
				</pre>
				<p>Avec notamment :</p>
				<ul>
					<li>La constante <b>FOREIGNKEYS_PREFIX</b> permet aux outils d'effectuer les jointures automatiquement. Cette constante est le préfixe utilisé pour les noms
						de colonnes qui contiennent les ID des relations à chercher.</li>
					<li>Ces jointures sont décrites dans la variable <b>$RELATIONS</b> : Un tableau associatif qui décrit les relations entre les colonnes préfixées
						avec "FK_" et les autres tables. Chaque entrée du tableau contient le nom de la colonne source associé à un tableau, dont la clé "table" est le nom de
						la table de destination, et la clé "alias", le nom que prendra cette destination. Veillez à ce que l'alias soit différent du nom de la colonne source.</li>
					<li>Enfin, la variable <b>$DATE_FIELDS</b> est une liste des colonnes qui contiennent des dates au format SQL, susceptibles d'être reformatées au format ISO 8601.</li>
				</ul>
			</article>
		</section>
		<br />
		<hr>
		<a id="listing"></a>
		<section>
			<h2>Listing</h2>
			<article>
				<a id="L1"></a>
				<h3>Utilisation la plus simple</h3>
				<p>Voici la plus simple façon d'utiliser l'objet "Listing" :</p>
				<pre>
<span class="var">$l</span> = <span class="operator">new</span> <span class="function">Listing</span>();
<span class="var">$users</span> = <span class="var">$l</span><span class="operator">-></span><span class="function">getListe</span>(<span class="argument">"users"</span>);

<span class="operator">print_r</span>(<span class="var">$users</span>);</pre>
				<p>Ceci retournera <b>toutes les entrées</b> de la table "users", comme cela :</p>
				<pre>Array (
    [0] => Array (
        [id] => 1
        [name] => Paul
        [pseudo] => Polo
        [age] => 34
        [last_action] => 2016-01-14T00:00:00+01:00
        [alive] => 1
    )
    [1] => Array ( .... )
    [2] => Array ( .... )
    [3] => Array ( .... )
    [4] => Array (
        [id] => 5
        [name] => Henri
        [pseudo] => Riton
        [age] => 30
        [last_action] => 2015-09-22T00:00:00+02:00
        [alive] => 1
    )
)</pre>
				<p>
					C'est une simple liste de toutes les entrées de la table, non triée. Chaque ligne de cette liste contient toutes les infos de chaque entrée sous forme
					de tableau associatif.
				</p>
				<p>
					Vous pouvez <b>utiliser une colonne</b> en tant qu'<b>index</b> du tableau (tableau associatif). Veillez cependant à ce que cette colonne soit un <u><i>index Unique</i></u>
					de la table, afin de ne pas écraser des lignes. Pour utiliser la colonne "name" en tant qu'index du tableau, il suffit d'utiliser la fonction <b>"simplyList"</b>,
					de cette manière :
				</p>
				<pre>
<span class="var">$l</span> = <span class="operator">new</span> <span class="function">Listing</span>();
<span class="var">$l</span><span class="operator">-></span><span class="function">getListe</span>(<span class="argument">"users"</span>);

<span class="var">$users</span> = <span class="var">$l</span><span class="operator">-></span><span class="function">simplifyList</span>(<span class="argument">"users"</span>);

<span class="operator">print_r</span>(<span class="var">$users</span>);</pre>
				<p>Ce qui retournera :</p>
				<pre>Array (
    [Paul] => Array (
        [id] => 1
        [name] => Paul
        [pseudo] => Polo
        [age] => 34
        [last_action] => 2016-01-14T00:00:00+01:00
        [alive] => 1
    )
    [Marcel]  => Array ( .... )
    [Jacques] => Array ( .... )
    [Julie]   => Array ( .... )
    [Henri]   => Array (
        [id] => 5
        [name] => Henri
        [pseudo] => Riton
        [age] => 30
        [last_action] => 2015-09-22T00:00:00+02:00
        [alive] => 1
    )
)</pre>
				<p>
					Pour ne récupérer que <b>certaines colonnes</b>, il faut utiliser le 2eme argument de la fonction getListe(), à savoir <span class="argument">$want</span>.
					Par exemple, pour ne récupérer que le nom et le pseudo des utilisateurs, nous ferons :
				</p>
				<pre>
<span class="var">$l</span> = <span class="operator">new</span> <span class="function">Listing</span>();
<span class="var">$users</span> = <span class="var">$l</span><span class="operator">-></span><span class="function">getListe</span>(<span class="argument">"users"</span>, <span class="argument">"name,pseudo"</span>);

<span class="operator">print_r</span>(<span class="var">$users</span>);</pre>
				<p>Ce qui nous donnera :</p>
				<pre>Array(
    [0] => Array (
        [name] => Paul
        [pseudo] => Polo
    )
    [1] => Array (
        [name] => Marcel
        [pseudo] => Mamar
    )
    [2] => Array (
        [name] => Jacques
        [pseudo] => Jack
    )
    [3] => Array (
        [name] => Julie
        [pseudo] => Grenouille
    )
    [4] => Array (
        [name] => Henri
        [pseudo] => Riton
    )
)</pre>
				<p>
					Pour récupérer toutes les colonnes, il suffit que <span class="argument">$want</span> soit <i>null</i>, ou égal à la chaîne <span class="argument">"*"</span>
					(c'est le comportement par défaut).
				</p>
			</article>
			<article>
				<a id="L2"></a>
				<h3>Le tri des données</h3>
				<p>
					Voyons maintenant l'utilisation du tri et des filtres. Pour <b>trier les données</b>, il suffit d'ajouter les paramètres <span class="argument">$sortBy</span>
					et <span class="argument">$order</span>. Ici nous allons trier la liste des utilisateurs selon leur âge, du plus vieux au plus jeune :
				</p>
				<pre>
<span class="var">$l</span> = <span class="operator">new</span> <span class="function">Listing</span>();
<span class="var">$users</span> = <span class="var">$l</span><span class="operator">-></span><span class="function">getListe</span>(<span class="argument">"users"</span>, <span class="argument">"*"</span>, <span class="argument">"age"</span>, <span class="argument">"desc"</span>);

<span class="operator">print_r</span>(<span class="var">$users</span>);</pre>
				<p>Ce qui nous donnera :</p>
				<pre>Array (
    [0] => Array (
        [id] => 2
        [name] => Marcel
        [pseudo] => Mamar
        [age] => 75
        [last_action] => 2012-04-24T00:00:00+02:00
        [alive] => 0
    )
    [1] => Array ( ... <span class="comment">/* Paul, age => 34 */</span> ... )
    [2] => Array ( ... <span class="comment">/* Julie, age => 32 */</span> ... )
    [3] => Array ( ... <span class="comment">/* Henri, age => 30 */</span> ... )
    [4] => Array (
        [id] => 3
        [name] => Jacques
        [pseudo] => Jack
        [age] => 22
        [last_action] => 2014-10-28T00:00:00+01:00
        [alive] => 1
    )
)</pre>
				<p>
					Notez que par défaut, l'ordre du tri est <span class="argument">"asc"</span> (ascendant). Il suffit alors d'ignorer <span class="argument">$order</span>,
					ou bien de lui passer <i>null</i>, ou encore la chaîne <span class="argument">"asc"</span>.
				</p>
			</article>
			<article>
				<a id="L3"></a>
				<h3>Le filtrage des données</h3>
				<p>
					Pour <b>filtrer les données</b>, nous utiliserons les trois paramètres suivants : <span class="argument">$filter_key</span>, <span class="argument">$filter_comp</span>,
					et <span class="argument">$filter</span> de la méthode <span class="function">getListe</span>().<br />
					Ainsi, pour ne récupérer rapidement que les utilisateurs dont l'âge est supérieur ou égal à 34 ans, nous pouvons faire :
				</p><pre>
<span class="var">$l</span> = <span class="operator">new</span> <span class="function">Listing</span>();
<span class="var">$users</span> = <span class="var">$l</span><span class="operator">-></span><span class="function">getListe</span>(<span class="argument">"users"</span>, <span class="argument">"*"</span>, <span class="argument">"last_action"</span>, <span class="argument">"desc"</span>, <span class="argument">"age"</span>, <span class="argument">">="</span>, <span class="argument">34</span>);

<span class="operator">print_r</span>(<span class="var">$users</span>);</pre>
				<p>Il en résultera :</p>
				<pre>Array (
    [0] => Array (
        [id] => 1
        [name] => Paul
        [pseudo] => Polo
        [age] => 34
        [last_action] => 2016-01-14T00:00:00+01:00
        [alive] => 1
    )
    [1] => Array (
        [id] => 2
        [name] => Marcel
        [pseudo] => Mamar
        [age] => 75
        [last_action] => 2012-04-24T00:00:00+02:00
        [alive] => 0
    )
)</pre>
				<p>
					Nous avons donc seulement les deux utilisateurs dont l'âge est supérieur ou égal à 34 ans, triés selon la date de leur dernière activité ('last_action').
					Notez qu'on a utilisé l'opérateur <span class="argument">">="</span>. Cet opérateur correspond à celui utilisé en SQL. Il est donc aussi possible d'utiliser
					<span class="argument">"<="</span>, <span class="argument">"<"</span>, <span class="argument">">"</span>, <span class="argument">"="</span>,
					<span class="argument">"!="</span>, <span class="argument">"LIKE"</span>, <span class="argument">"BETWEEN"</span>, etc.
				</p>
				<p>
					Maintenant, comment faire si nous voulons seulement les utilisateurs agés d'au moins 30 ans <b>mais</b> qui sont toujours en vie ?<br />
					C'est là qu'entre en scène le <b>filtrage multiple</b> ! Pour cela nous allons utiliser la méthode <span class="function">addFiltre</span>().
					Cette méthode peut être utilisée autant de fois que l'on veut, avant l'appel à <span class="function">getListe</span>(). Une fois qu'on a défini
					un filtre avec cette méthode, il n'est plus nécessaire d'utiliser le filtrage interne à <span class="function">getListe</span>().<br />
					Reprenons notre exemple :
				</p>
				<pre>
<span class="var">$l</span> = <span class="operator">new</span> <span class="function">Listing</span>();

<span class="var">$l</span><span class="operator">-></span><span class="function">addFiltre</span>(<span class="argument">"age"</span>,   <span class="argument">">"</span>, <span class="argument">30</span>);
<span class="var">$l</span><span class="operator">-></span><span class="function">addFiltre</span>(<span class="argument">"alive"</span>, <span class="argument">"="</span>,  <span class="argument">1</span>);

<span class="var">$users</span> = <span class="var">$l</span><span class="operator">-></span><span class="function">getListe</span>(<span class="argument">"users"</span>, <span class="argument">"*"</span>, <span class="argument">"last_action"</span>, <span class="argument">"desc"</span>);
<span class="operator">print_r</span>(<span class="var">$users</span>);</pre>
				<p>Ce qui nous retournera :</p>
				<pre>Array (
    [0] => Array (
        [id] => 1
        [name] => Paul
        [pseudo] => Polo
        [age] => 34
        [last_action] => 2016-01-14T00:00:00+01:00
        [alive] => 1
    )
    [1] => Array (
        [id] => 4
        [name] => Julie
        [pseudo] => Grenouille
        [age] => 32
        [last_action] => 2015-10-28T00:00:00+01:00
        [alive] => 1
    )
)</pre>
				<p>
					Ensuite, si pour une raison ou une autre, vous devez enlever ou modifier les filtres, il suffit d'utiliser la méthode <span class="function">resetFiltre</span>().<br />
					Exemple d'utilisation :
				</p>
				<pre>
<span class="var">$l</span> = <span class="operator">new</span> <span class="function">Listing</span>();

<span class="var">$l</span><span class="operator">-></span><span class="function">addFiltre</span>(<span class="argument">"age"</span>,   <span class="argument">">"</span>, <span class="argument">30</span>);
<span class="var">$l</span><span class="operator">-></span><span class="function">addFiltre</span>(<span class="argument">"alive"</span>, <span class="argument">"="</span>,  <span class="argument">1</span>);
<span class="var">$older_users</span> = <span class="var">$l</span><span class="operator">-></span><span class="function">getListe</span>(<span class="argument">"users"</span>);

<span class="var">$l</span><span class="operator">-></span><span class="function">resetFiltre</span>();

<span class="var">$l</span><span class="operator">-></span><span class="function">addFiltre</span>(<span class="argument">"age"</span>,   <span class="argument">"<="</span>, <span class="argument">30</span>);
<span class="var">$youger_users</span> = <span class="var">$l</span><span class="operator">-></span><span class="function">getListe</span>(<span class="argument">"users"</span>);</pre>
				<p>
					Ainsi la variable <span class="var">$older_users</span> contiendra un tableau avec les utilisateurs dont l'âge est supérieur à 30 ans, mais qui sont
					toujours en vie, et la variable <span class="var">$youger_users</span> contiendra ceux dont l'âge est inférieur ou égal à 30 ans.
				</p>
				<p>
					Il est bien entendu possible de modifier le comportement d'ajout des filtres. Par défaut, l'opérande utilisée entre chaque filtre est <b>"AND"</b>. Vous
					pouvez spécifier n'importe quelle opérande avec le 4eme argument, <span class="argument">$logique</span> ('OR', 'NAND', 'NOR'...).
				</p>
				<p>
					Enfin, si vous avez besoin d'utiliser une <b>fonction SQL</b> dans le filtrage, vous avez la possibilité d'utiliser la méthode <span class="function">setFiltreSQL</span>().<br />
					Voici un exemple :
				</p>
				<pre>
<span class="var">$l</span> = <span class="operator">new</span> <span class="function">Listing</span>();

<span class="var">$l</span><span class="operator">-></span><span class="function">setFiltreSQL</span>(<span class="argument">"`age` >= 30 AND `last_action` <= DATE_ADD(NOW(), INTERVAL -6 MONTH)"</span>);
<span class="var">$users</span> = <span class="var">$l</span><span class="operator">-></span><span class="function">getListe</span>(<span class="argument">"users"</span>);</pre>
				<p>
					Ce qui nous donnera la liste des utilisateurs agés de plus de 30 ans, et dont l'activité la plus récente date d'au moins 6 mois.<br />
					Cependant, utilisez cette méthode avec prudence, surtout si vous incluez des variables dans le paramètre <span class="argument">$filtre</span>, car cela
					ouvre des possibilités d'injection SQL. Assurez-vous au préalable que les variables sont saines.
				</p>
			</article>
			<article>
				<a id="L4"></a>
				<h3>Les jointures</h3>

				<pre><?php
//					$l = new Listing();
//					$l->setFiltreSQL("`last_action` <= DATE_ADD(NOW(), INTERVAL -6 MONTH)");
//					$users = $l->getListe("users", "*", 'last_action', 'desc');
//					print_r($users);
				?></pre>

				<p>
				</p>
			</article>
		</section>
		<br />
		<hr>
		<a id="infos"></a>
		<section>
			<h2>Infos</h2>
		</section>
	</div>
</body>
</html>