<? session_start();

?>

<!--	imports	-->
<link rel="stylesheet" type="text/css" href="lib/style.css" />
<script type="text/javascript"> var last_seen = "<?php echo $_SESSION["seen"]; ?>";</script>
<script type="text/javascript"> var n = 10 </script>
<script type="text/javascript" src="lib/script.js"></script>

<!--	header	-->

<? include("lib/header.php")?>

<!--	content	-->
<body onLoad="getTopArticles( n )">
<table><thead>
<tr>
	<td><b>Published</b></td>
	<td><b>Article Title</b></td>
	<td><b>Channel</b></td>
	<td align="right"><b>Hide</b></td>
</tr>
</thead><tbody id="posttable" >
<tr>
	<td></td>
	<td id="bottomText" colspan="4">RSS-feed will be displayed here [...]</td>
</tr>
</tbody>
<tfoot>
<? if ( isset($_SESSION["token"] ) ) { ?>
<tr height="6em"></tr><tr>
<td>
<a href="#" onclick="refreshArticles()">refresh</a>
</td><td colspan="2"><td align="right">
<a href="#" onclick="getTopArticles( n=n+10 )">more[+]</a>
</td>
<? } ?>
</tfoot>
</table>
</body>
