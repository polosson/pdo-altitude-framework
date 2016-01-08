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
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="author" content="Polosson">
	<title>PDO ALTITUDE - DOC</title>
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
			<li><a href="../../doc/en/index.php">DOCUMENTATION ></a></li>
		</ul>
	</header>
	<div class="menu-left">
		<h1>PDO Altitude<br />Examples</h1>
		<hr>
		<ol>
			<li><a href="#top">Introduction</a>
				<ol>
					<li><a href="#I1">Database</a></li>
					<li><a href="#I2">Configuration</a></li>
				</ol>
			</li>
			<li><a href="#listing">Listing</a>
				<ol>
					<li><a href="#L1">The most simple</a></li>
					<li><a href="#L2">Filtering</a></li>
					<li><a href="#L3">Joints</a></li>
				</ol>
			</li>
			<li><a href="#infos">Infos</a>
				<ol>
					<li><a href="#F1">Modify an entry</a></li>
					<li><a href="#F2">Create an entry</a></li>
					<li><a href="#F3">Adding columns</a></li>
				</ol>
			</li>
		</ol>
	</div>
	<div class="content">
		<section>
			<h1>Introduction</h1>
			<article>
				<a id="I1"></a>
				<h3>Database for the examples</h3>
				<p>To demonstrate, we will use a database which contains 3 SQL tables. Here they are:</p>
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
						<td>5</td><td>itemZaa</td><td>5</td><td>3</td><td>2015-12-05</td><td>{7,356,20,16}</td>
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
				<h3>Configuration for the examples</h3>
				<p>Before we can use the tools, we must define the configuration contants and variables. We will use the following configuration:</p>
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
<span class="var">$DATE_FIELDS</span> = <span class="operator">Array</span>(<span class="argument">"date"</span>, <span class="argument">"last_action"</span>, <span class="argument">"date_creation"</span>);

<span class="comment">/**
 * Including classes files
 */</span>
<span class="function">require</span>(<span class="argument">"classes/Listing.class.php"</span>);
<span class="function">require</span>(<span class="argument">"classes/Infos.class.php"</span>);
				</pre>
				<p>With notably:</p>
				<ul>
					<li>The constant <b>FOREIGNKEYS_PREFIX</b> allows the tools to do joints automatically. This constant is the prefix used for the columns that contains
						the ID of relations to find.</li>
					<li>These joints are described in the variable <b>$RELATIONS</b> : An associative array which describe the relationships between the "FK_" prefixed columns
						and the other tables. Each entries of this array contains the name of the source column associated to an array, whith key "table" for the destination
						table name, and key "alias" for the name given to the destination. Make sure the alias is different from the source column name.</li>
					<li>And, the variable <b>$DATE_FIELDS</b> is a list of columns which contains SQL formated dates, which may be reformated into ISO 8601.</li>
				</ul>
			</article>
		</section>
		<br />
		<hr>
		<a id="listing"></a>
		<section>
			<h1>Listing</h1>
		</section>
		<br />
		<hr>
		<a id="infos"></a>
		<section>
			<h1>Infos</h1>
		</section>
	</div>
</body>
</html>