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
			<li><a href="../../examples/fr/index.php">EXAMPLES ></a></li>
		</ul>
	</header>
	<div class="menu-left">
		<h1>PDO Altitude<br />Documentation</h1>
		<hr>
		<ol>
			<li><a href="#top">Introduction</a>
				<ol>
					<li><a href="#I1">Inclusion and inheritance</a></li>
					<li><a href="#I2">Instanciation and prerequisites</a></li>
					<li><a href="#I3">Important concepts</a></li>
				</ol>
			</li>
			<li><a href="#listing">Class "Listing"</a>
				<ol>
					<li><a href="#C1P">Properties</a></li>
					<li><a href="#C1M">Methods</a>
						<ol>
							<li><a href="#C1M1">__contruct()</a></li>
							<li><a href="#C1M2">getList()</a></li>
							<li><a href="#C1M3">countResults()</a></li>
							<li><a href="#C1M4">addFilter()</a></li>
							<li><a href="#C1M5">addFilterRaw()</a></li>
							<li><a href="#C1M6">resetFilter()</a></li>
							<li><a href="#C1M7">setFilterSQL()</a></li>
							<li><a href="#C1M8">reindexList()</a></li>
							<li><a href="#C1M9">getCols()</a></li>
							<li><a href="#C1M10">getMax()</a></li>
							<li><a href="#C1M11">getAIval()</a></li>
							<li><a href="#C1M12">reindexById()</a></li>
							<li><a href="#C1M13">initPDO()</a></li>
							<li><a href="#C1M14">check_table_exists()</a></li>
							<li><a href="#C1M15">check_col_exists()</a></li>
							<li><a href="#C1M16">getForeignKey()</a></li>
						</ol>
					</li>
				</ol>
			</li>
			<li><a href="#infos">Class "Infos"</a>
				<ol>
					<li><a href="#C2P">Properties</a></li>
					<li><a href="#C2M">Methods</a>
						<ol>
							<li><a href="#C2M1">__construct()</a></li>
							<li><a href="#C2M2">setTable()</a></li>
							<li><a href="#C2M3">getTable()</a></li>
							<li><a href="#C2M4">loadInfos()</a></li>
							<li><a href="#C2M5">isLoaded()</a></li>
							<li><a href="#C2M61">getInfo()</a></li>
							<li><a href="#C2M62">getManyInfos()</a></li>
							<li><a href="#C2M7">countInfos()</a></li>
							<li><a href="#C2M8">setInfo()</a></li>
							<li><a href="#C2M9">setManyInfos()</a></li>
							<li><a href="#C2M10">save()</a></li>
							<li><a href="#C2M11">delete()</a></li>
							<li><a href="#C2M12">colExists()</a></li>
							<li><a href="#C2M13">colIndex_isUnique()</a></li>
							<li><a href="#C2M14">addNewCol()</a></li>
							<li><a href="#C2M15">removeCol()</a></li>
							<li><a href="#C2M16">createMissingCols()</a></li>
							<li><a href="#C2M17">autoAddCol()</a></li>
						</ol>
					</li>
				</ol>
			</li>
			<li><a href="#licence">Licence</a></li>
		</ol>
	</div>
	<div class="content">
		<?php include('Intro_en.html'); ?>
		<br />
		<hr>
		<?php include('Listing_en.html'); ?>
		<br />
		<hr>
		<?php include('Infos_en.html'); ?>
		<br />
		<hr>
		<?php include('Licence_en.html'); ?>
		<br /><br /><br /><br /><br /><br />
	</div>
</body>
</html>