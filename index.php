<? session_start();

$loggedin = false;
if (isset($_SESSION["token"])) {
	$loggedin = true;
}

?>

<!--	imports	-->
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript" src="lib/script.js"></script>

<!--	header	-->

<? include("header.php")?>

<!--	content	-->
<body onLoad="getFeed()">
<table><thead>
<tr>
	<td><b>Published</b></td>
	<td><b>Article Title</b></td>
	<td><b>Channel</b></td>
	<td align="right"><b>Star</b></td>
	<td align="right"><b>Hide</b></td>
</tr>
</thead><tbody id="posttable" >
<tr>
	<td></td>
	<td id="bottomText" colspan="4">RSS-feed will be displayed here [...]</td>
</tr>
</tbody></table>
<? if ( $loggedin ) { ?>
&emsp;&emsp;<a href="#" onclick="getFeed()">refresh</a>
<? } ?>
</body>
