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
	<title>PDO ALTITUDE - EXAMPLES</title>
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
					<li><a href="#L1">Simple use case</a></li>
					<li><a href="#L2">Sorting</a></li>
					<li><a href="#L3">Filtering</a></li>
					<li><a href="#L4">Joints</a></li>
				</ol>
			</li>
			<li><a href="#infos">Infos</a>
				<ol>
					<li><a href="#F1">Get an entry</a></li>
					<li><a href="#F2">Modify an entry</a></li>
					<li><a href="#F3">Create an entry</a></li>
					<li><a href="#F4">Remove an entry</a></li>
				</ol>
			</li>
		</ol>
	</div>
	<div class="content">
		<section>
			<h2>Introduction</h2>
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
<span class="function">define</span>(<span class="argument">"DATE_CREATION"</span>, <span class="argument">"date_creation"</span>);
<span class="function">define</span>(<span class="argument">"LAST_UPDATE"</span>, <span class="argument">"last_action"</span>);
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
					<li>The two constants <b>DATE_CREATION</b> and <b>LAST_UPDATE</b> are defined to use the automatic update of creation date and last update time.
						And, the variable <b>$DATE_FIELDS</b> is a list of columns which contains SQL formated dates, which may be reformated into ISO 8601.</li>
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
				<h3>Simple use case</h3>
				<p>Here is the most simple way to use the object "Listing":</p>
				<pre>
<span class="var">$l</span> = <span class="operator">new</span> <span class="function">Listing</span>();
<span class="var">$users</span> = <span class="var">$l</span><span class="operator">-></span><span class="function">getListe</span>(<span class="argument">"users"</span>);

<span class="operator">print_r</span>(<span class="var">$users</span>);</pre>
				<p>This will return <b>all the entries</b> of table "users", like so:</p>
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
					It's a simple list of all entries of the table, unsorted. Each line of this list contains all informations of each entries, on the form of
					a associative array.
				</p>
				<p>
					You can <b>use a column</b> as <b>index</b> of the array (associative array). However, make sure that this column is a <u><i>Unique index</i></u>
					for the table, so as not to overwrite some lines. To use the column "name" as index for the array, simply use the method
					<span class="function">simplyList</span>(), this way:
				</p>
				<pre>
<span class="var">$l</span> = <span class="operator">new</span> <span class="function">Listing</span>();
<span class="var">$l</span><span class="operator">-></span><span class="function">getListe</span>(<span class="argument">"users"</span>);

<span class="var">$users</span> = <span class="var">$l</span><span class="operator">-></span><span class="function">simplifyList</span>(<span class="argument">"users"</span>);

<span class="operator">print_r</span>(<span class="var">$users</span>);</pre>
				<p>Which will return:</p>
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
					To get only <b>some specific columns</b>, you must set the 2nd parameter of the method <span class="function">getListe</span>(), namely
					<span class="argument">$want</span>. For instance, to get only the name and pseudo of all users, we'll do:
				</p>
				<pre>
<span class="var">$l</span> = <span class="operator">new</span> <span class="function">Listing</span>();
<span class="var">$users</span> = <span class="var">$l</span><span class="operator">-></span><span class="function">getListe</span>(<span class="argument">"users"</span>, <span class="argument">"name,pseudo"</span>);

<span class="operator">print_r</span>(<span class="var">$users</span>);</pre>
				<p>Which will give us:</p>
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
					To get all columns, you just have to ommit <span class="argument">$want</span>, or give it the string
					<span class="argument">"*"</span> (it's the default behavior).
				</p>
			</article>
			<article>
				<a id="L2"></a>
				<h3>Sorting data</h3>
				<p>
					Let's see now the use of sorting. To <b>sort data</b>, we just have to add the parameters <span class="argument">$sortBy</span> and
					<span class="argument">$order</span>. Here we'll sort the list of users according to their age, from the oldest to the youngest:
				</p>
				<pre>
<span class="var">$l</span> = <span class="operator">new</span> <span class="function">Listing</span>();
<span class="var">$users</span> = <span class="var">$l</span><span class="operator">-></span><span class="function">getListe</span>(<span class="argument">"users"</span>, <span class="argument">"*"</span>, <span class="argument">"age"</span>, <span class="argument">"desc"</span>);

<span class="operator">print_r</span>(<span class="var">$users</span>);</pre>
				<p>Which will gie us:</p>
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
					Please note that by default, the sort order is <span class="argument">"asc"</span> (ascending). If you want ascending, just then ignore
					<span class="argument">$order</span>, or give it the string <span class="argument">"asc"</span>.
				</p>
			</article>
			<article>
				<a id="L3"></a>
				<h3>Filtering data</h3>
				<p>
					To <b>filter data</b>, we'll use the three parameters <span class="argument">$filter_key</span>, <span class="argument">$filter_comp</span>,
					and <span class="argument">$filter_val</span> of method <span class="function">getListe</span>().<br />
					Thereby, to quickly get only the users whose age exceeds or equals 34 years old, we can do:
				</p><pre>
<span class="var">$l</span> = <span class="operator">new</span> <span class="function">Listing</span>();
<span class="var">$users</span> = <span class="var">$l</span><span class="operator">-></span><span class="function">getListe</span>(<span class="argument">"users"</span>, <span class="argument">"*"</span>, <span class="argument">"last_action"</span>, <span class="argument">"desc"</span>, <span class="argument">"age"</span>, <span class="argument">">="</span>, <span class="argument">34</span>);

<span class="operator">print_r</span>(<span class="var">$users</span>);</pre>
				<p>It will results in:</p>
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
					So we have only the two users whose age exceeds or equals 34 years old, sorted by their last activity date ('last_action').
					Please note that we have used the operand <span class="argument">">="</span>. This operand corresponds to thoses used in SQL. So it's also possible to use
					<span class="argument">"<="</span>, <span class="argument">"<"</span>, <span class="argument">">"</span>, <span class="argument">"="</span>,
					<span class="argument">"!="</span>, <span class="argument">"LIKE"</span>, <span class="argument">"BETWEEN"</span>, etc.
				</p>
				<p>
					OK now, how to only get the users aged at least 30 years <b>but</b> who are still alive?<br />
					Here comes the <b>multiple filtering</b>! To do so, we'll use the method <span class="function">addFiltre</span>().
					This method can be used as many times as we want, before the call to <span class="function">getListe</span>(). Once a filter is
					defined with this method, it's no longer necessary to use filtering inside <span class="function">getListe</span>().<br />
					Let's return to our example:
				</p>
				<pre>
<span class="var">$l</span> = <span class="operator">new</span> <span class="function">Listing</span>();

<span class="var">$l</span><span class="operator">-></span><span class="function">addFiltre</span>(<span class="argument">"age"</span>,   <span class="argument">">"</span>, <span class="argument">30</span>);
<span class="var">$l</span><span class="operator">-></span><span class="function">addFiltre</span>(<span class="argument">"alive"</span>, <span class="argument">"="</span>,  <span class="argument">1</span>);

<span class="var">$users</span> = <span class="var">$l</span><span class="operator">-></span><span class="function">getListe</span>(<span class="argument">"users"</span>, <span class="argument">"*"</span>, <span class="argument">"last_action"</span>, <span class="argument">"desc"</span>);
<span class="operator">print_r</span>(<span class="var">$users</span>);</pre>
				<p>Which will returns:</p>
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
					Next, if for one reason or another, you must remove or modify filters, you just have to use the method <span class="function">resetFiltre</span>().<br />
					Example of use:
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
					Thereby the variable <span class="var">$older_users</span> will contain an array with users aged at least 30 years old, but who are still alive, and the
					variable <span class="var">$youger_users</span> will contain users whose age is below or equals to 30 years old.
				</p>
				<p>
					It's of course possible to modify the way filters are added. By default, the operand used between each filter is <b>"AND"</b>. You can specify
					any other operan with the 4th parameter, <span class="argument">$logique</span> ('OR', 'NAND', 'NOR'...).
				</p>
				<p>
					Finally, if you need to use a <b>SQL function</b> in the filter, you have the possibility to use the method <span class="function">setFiltreSQL</span>().<br />
					Here is an example:
				</p>
				<pre>
<span class="var">$l</span> = <span class="operator">new</span> <span class="function">Listing</span>();

<span class="var">$l</span><span class="operator">-></span><span class="function">setFiltreSQL</span>(<span class="argument">"`age` >= 30 AND `last_action` <= DATE_ADD(NOW(), INTERVAL -6 MONTH)"</span>);
<span class="var">$users</span> = <span class="var">$l</span><span class="operator">-></span><span class="function">getListe</span>(<span class="argument">"users"</span>);</pre>
				<p>
					Which will give us the list of users aged at least 30 years old, and whose the most recent activity is at least 6 month old.<br />
					However, use this method carefully, especially if you include some variables in the parameter <span class="argument">$filtre</span>, because it
					opens possibilities of SQL injection. Make sure beforehand that the variables are safe.
				</p>
			</article>
			<article>
				<a id="L4"></a>
				<h3>Joints</h3>
				<p>
					To illustrate joints, let's take the table <b>comments</b>. This table has two <b>columns, prefixed</b> with string <b>"FK_"</b>. This prefix
					has been given in constant <a href="#I2"><span class="argument">FOREIGNKEYS_PREFIX</span></a>. The two columns that interest us are
					<b>"FK_user_ID"</b> et <b>"FK_item_ID"</b>. Those two columns are present in the array <a href="#I2"><span class="argument">$RELATIONS</span></a>,
					allowing Altitude to establish the relationship between these columns and the associated tables. If this array doesn't exists, joints will be
					ignored, and the columns will only contain the IDs.<br />
					Reminder of the array, written at configuration time:
				</p>
				<pre><span class="var">$RELATIONS</span> = <span class="operator">Array</span>(
    <span class="argument">"FK_user_ID"</span>	=> <span class="operator">Array</span>('table' => <span class="argument">"users"</span>,	'alias' => <span class="argument">"user"</span>),
    <span class="argument">"FK_item_ID"</span>	=> <span class="operator">Array</span>('table' => <span class="argument">"items"</span>,	'alias' => <span class="argument">"item"</span>),
    <span class="argument">"FK_comment_ID"</span>	=> <span class="operator">Array</span>('table' => <span class="argument">"comments"</span>,	'alias' => <span class="argument">"comment"</span>)
);</pre>
				<p>
					Right. Now, let's say we want to get the list of comments, but we want at the same time to retreive all informations of the user who wrote it,
					and the informations about the item which was commented on. To do so, nothing more simple:
				</p>
				<pre>
<span class="var">$l</span> = <span class="operator">new</span> <span class="function">Listing</span>();

<span class="var">$comments</span> = <span class="var">$l</span><span class="operator">-></span><span class="function">getListe</span>(<span class="argument">"comments"</span>);
<span class="operator">print_r</span>(<span class="var">$comments</span>);</pre>
				<p>And we've got:</p>
				<pre>Array (
    [0] => Array (
            [id] => 1
            [date] => 2015-11-21T00:00:00+01:00
            [FK_user_ID] => 1
            [FK_item_ID] => 4
            [text] => What a nice comment, gniuk gniuk!
            [user] => Array
                (
                    [id] => 1
                    [name] => Paul
                    [pseudo] => Polo
                    [age] => 34
                    [last_action] => 2016-01-14
                    [alive] => 1
                )

            [item] => Array
                (
                    [id] => 4
                    [ref] => itemNiuk
                    [FK_user_ID] => 3
                    [FK_comment_ID] => 1
                    [date_creation] => 2015-01-14
                    [content] => {"leski":"mow","gniuk":"gniuk"}
                )
        )
    [1] => Array (...
    [2] => Array (...
    ... etc.</pre>
				<p>
					Basically, there is nothing to do, the joints are automatically resolved. Note that columns "FK_user_ID" et "FK_item_ID" are
					present, but the array contains two additionnal lines: <b>"user"</b>, and <b>"item"</b>. These key names correspond to
					the value <span class="argument">"alias"</span> of the correspondence table <span class="argument">$RELATIONS</span>.
				</p>
				<p>
					If, for some reason, you <b>don't want</b> to get joints, you just have to set the parameter <span class="argument">$withFK</span>
					(9th position) to <b>FALSE</b>.
				</p>
			</article>
		</section>
		<br />
		<hr>
		<a id="infos"></a>
		<section>
			<h2>Infos</h2>
			<article>
				<a id="F1"></a>
				<h3>Get an entry</h3>
				<p>
					Pour récupérer les informations d'une <b>entrée particulière d'une table</b> de la base de données, il faut avant tout en connaître un
					<b>identifiant unique</b>, comme par exemple son 'id', ou bien le nom ou l'email d'un utilisateur, ou encore la référence d'un item. En
					effet, si l'objet "Infos" trouve <b>plusieurs entrées</b> avec l'identifiant donné, il renvoie une <b>erreur</b>.
				</p>
				<p>
					Dans notre exemple nous utiliserons l'identifiant unique de la colonne "id". Voici comment procéder pour charger une entrée de la base
					de données en mémoire :
				</p>
				<pre><span class="var">$i</span> = <span class="operator">new</span> <span class="function">Infos</span>(<span class="argument">"users"</span>);

<span class="var">$i</span><span class="operator">-></span><span class="function">loadInfos</span>(<span class="argument">"id"</span>, <span class="argument">"3"</span>);
<span class="var">$user</span> = <span class="var">$i</span><span class="operator">-></span><span class="function">getInfos</span>();

<span class="operator">print_r</span>(<span class="var">$user</span>);</pre>
				<p>Il en résultera le tableau suivant :</p>
				<pre>Array (
    [id] => 3
    [name] => Jacques
    [pseudo] => Jack
    [age] => 22
    [last_action] => 2014-10-28T00:00:00+01:00
    [alive] => 1
)</pre>
				<p>
					Utiliser la méthode <span class="function">getInfos</span>() sans paramètre permet d'avoir toutes les colonnes. C'est le comportement par défaut.
					Cependant, il est possible de ne récupérer qu'une seule colonne, grâce au paramètre <span class="argument">$champ</span>, comme ceci :
				</p>
				<pre><span class="var">$i</span> = <span class="operator">new</span> <span class="function">Infos</span>(<span class="argument">"users"</span>);
<span class="var">$i</span><span class="operator">-></span><span class="function">loadInfos</span>(<span class="argument">"id"</span>, <span class="argument">3</span>);

<span class="var">$userPseudo</span> = <span class="var">$i</span><span class="operator">-></span><span class="function">getInfos</span>(<span class="argument">"pseudo"</span>);

<span class="operator">print_r</span>(<span class="var">$userPseudo</span>);</pre>
				<p>Ce qui donnera :</p>
				<pre>Jack</pre>
				<p>
					... Tellement simple. Mais qu'en est-il des <b>jointures</b> ? De la même manière que l'objet "Listing", les jointures se font automatiquement.
					Ainsi, au moment de l'appel à la méthode <span class="function">loadInfos</span>(), les jointures sont résolues directement, et sont disponibles
					en mémoire. Pour vous le montrer, utilisons le même appel que précédemment sur la table "comments" :
				</p>
				<pre><span class="var">$i</span> = <span class="operator">new</span> <span class="function">Infos</span>(<span class="argument">"comments"</span>);
<span class="var">$i</span><span class="operator">-></span><span class="function">loadInfos</span>(<span class="argument">"id"</span>, <span class="argument">3</span>);

<span class="var">$comment</span> = <span class="var">$i</span><span class="operator">-></span><span class="function">getInfos</span>();

<span class="operator">print_r</span>(<span class="var">$comment</span>);</pre>
				<p>Ce qui donne :</p>
				<pre>Array (
    [id] => 3
    [date] => 2015-12-23T00:00:00+01:00
    [FK_user_ID] => 5
    [FK_item_ID] => 5
    [text] => Wazzaaaaa!
    [user] => Array (
        [id] => 5
        [name] => Henri
        [pseudo] => Riton
        [age] => 30
        [last_action] => 2015-09-22
        [alive] => 1
    )

    [item] => Array (
        [id] => 5
        [ref] => itemZaa
        [FK_user_ID] => 5
        [FK_comment_ID] => 3
        [date_creation] => 2015-12-05
        [content] => [7,356,20,16]
    )
)</pre>
				<p>Magnifique. Cela veut dire que plus tard dans le code, nous pourrons aussi faire :</p>
				<pre><span class="var">$i</span><span class="operator">-></span><span class="function">getInfos</span>(<span class="argument">"user"</span>)</pre>
				<p>Qui nous retournera un seul tableau contenant toutes les informations de l'utilisateur qui a écrit le commentaire !</p>
			</article>
			<article>
				<a id="F2"></a>
				<h3>Modifier une entrée</h3>
				<p>
					Nous allons modifier le pseudo de l'utilisateur dont le nom est "Marcel". Pour cela il faut d'abord le <b>charger en mémoire</b>, puis <b>modifier</b> son
					pseudo, et enfin <b>sauvegarder</b> la modification dans la base de données. Voici comment procéder :
				</p>
				<pre><span class="var">$i</span> = <span class="operator">new</span> <span class="function">Infos</span>(<span class="argument">"users"</span>);
<span class="var">$i</span><span class="operator">-></span><span class="function">loadInfos</span>(<span class="argument">"id"</span>, <span class="argument">2</span>);

<span class="var">$i</span><span class="operator">-></span><span class="function">setInfo</span>(<span class="argument">"pseudo"</span>, <span class="argument">"Marcello"</span>);
<span class="var">$i</span><span class="operator">-></span><span class="function">save</span>();</pre>
				<p>
					La méthode <span class="function">setInfo</span>() permet d'ajouter ou de modifier une colonne de l'entrée chargée en mémoire.
					Notez que si vous mettez le nom d'une <b>colonne qui n'existe pas</b> comme premier paramètre, elle sera <b>automatiquement ajoutée</b>
					à la structure de la table.<br />
					La méthode <span class="function">save</span>() est celle qui va enregistrer la modification de l'entrée dans la base. Une fois que cette méthode a été
					éxécutée, il est <b>impossible de revenir en arrière</b>.<br />
					Si vous voulez <b>empêcher la création automatique</b> de colonne (dans le cas où le paramètre
					<span class="argument">$key</span> de <span class="function">setInfo</span>() est le nom d'une colonne inexistante), il vous suffit de mettre le
					3eme paramètre (<span class="argument">$addCol</span>) de <span class="function">save</span>() à FALSE.
				</p>
				<p>
					Il est aussi possible de modifier toutes les colonnes d'un seul coup, grâce à la méthode <span class="function">setAllInfos</span>(). Le paramètre
					<span class="argument">$newInfos</span> doit être un tableau associatif, la clé étant le nom de la colonne. Par exemple :
				</p>
				<pre><span class="var">$i</span> = <span class="operator">new</span> <span class="function">Infos</span>(<span class="argument">"users"</span>);
<span class="var">$i</span><span class="operator">-></span><span class="function">loadInfos</span>(<span class="argument">"id"</span>, <span class="argument">2</span>);

<span class="var">$newInfos</span> = <span class="operator">Array</span>(
    "name" => <span class="argument">"Marcelle"</span>,
    "pseudo" => <span class="argument">"Marcie"</span>,
    "age" => <span class="argument">69</span>
);
<span class="var">$i</span><span class="operator">-></span><span class="function">setAllInfos</span>(<span class="argument">$newInfos</span>);
<span class="var">$i</span><span class="operator">-></span><span class="function">save</span>();</pre>
				<p>
					Comme pour <span class="function">setInfo</span>(), il faut sauvegarder les changements pour qu'ils soient pris en compte dans la base avec
					<span class="function">save</span>(). Une fois sauvegardé, on ne peut plus revenir en arrière.
				</p>
			</article>
			<article>
				<a id="F3"></a>
				<h3>Créer une entrée</h3>
				<p>
					Pour ajouter une entrée dans la base de données, il suffit de faire comme précédemment, mais <b>sans utiliser la méthode</b>
					<span class="function">loadInfos</span>(). Par exemple, ajoutons un utilisateur "Alex" dans la table "users" :
				</p>
				<pre><span class="var">$i</span> = <span class="operator">new</span> <span class="function">Infos</span>(<span class="argument">"users"</span>);

<span class="var">$newInfos</span> = <span class="operator">Array</span>(
    "name" => <span class="argument">"Alex"</span>,
    "pseudo" => <span class="argument">"AK"</span>,
    "age" => <span class="argument">29</span>
);
<span class="var">$i</span><span class="operator">-></span><span class="function">setAllInfos</span>(<span class="argument">$newInfos</span>);
<span class="var">$i</span><span class="operator">-></span><span class="function">save</span>();

<span class="var">$user</span> = <span class="var">$i</span><span class="operator">-></span><span class="function">getInfos</span>();
<span class="operator">print_r</span>(<span class="var">$user</span>);</pre>
				<p>Il en résultera l'entrée suivante :</p>
				<pre>Array (
    [id] => 6
    [name] => Alex
    [pseudo] => AK
    [age] => 29
    [last_action] => 0000-00-00T00:00:00+00:00
    [alive] => 0
)</pre>
				<p>
					Vous vous serez aperçu que les deux colonnes que nous n'avons pas renseigné ont pris les valeurs par défaut de la table SQL.
				</p>
			</article>
			<article>
				<a id="F4"></a>
				<h3>Supprimer une entrée</h3>
				<p>
					Pour supprimer une entrée, nous utiliserons la méthode <span class="function">delete</span>(). Cependant attention ! Cette
					action est <b>irréversible</b>. Pour notre exemple, supprimons l'entrée que nous venons de créer au paragraphe précédent :
				</p>
				<pre><span class="var">$i</span> = <span class="operator">new</span> <span class="function">Infos</span>(<span class="argument">"users"</span>);
<span class="var">$i</span><span class="operator">-></span><span class="function">loadInfos</span>(<span class="argument">"id"</span>, <span class="argument">6</span>);

<span class="var">$i</span><span class="operator">-></span><span class="function">delete</span>();</pre>
				<p>Et voilà, notre entrée a disparu de la base de données. Vous pouvez aussi utiliser cette fonction de la manière suivante :</p>
				<pre><span class="var">$i</span> = <span class="operator">new</span> <span class="function">Infos</span>(<span class="argument">"users"</span>);
<span class="var">$i</span><span class="operator">-></span><span class="function">delete</span>(<span class="argument">"id"</span>, <span class="argument">6</span>);</pre>
				<p>
					Cela aura le même effet que précédemment, mais nous avons spécifié la clé et la valeur de l'entrée à supprimer directement dans la
					méthode <span class="function">delete</span>(), sans utiliser <span class="function">loadInfos</span>(). Ce filtrage basique peut etre utile
					pour supprimer plusieurs entrées d'un seul coup, en spécifiant par exemple :
				</p>
				<pre><span class="var">$i</span> = <span class="operator">new</span> <span class="function">Infos</span>(<span class="argument">"users"</span>);
<span class="var">$i</span><span class="operator">-></span><span class="function">delete</span>(<span class="argument">"alive"</span>, <span class="argument">0</span>);</pre>
				<p>
					Ce qui supprimera tous les utilisateurs dont la colonne "alive" est à 0.<br />
					L'utilisation de la méthode <span class="function">loadInfos</span>() est donc facultative, mais elle permet d'éviter les erreurs et d'être certain
					de n'éffacer qu'une seule entrée.
				</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
			</article>
		</section>
	</div>
</body>
</html>