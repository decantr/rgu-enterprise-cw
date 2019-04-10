<?

$loggedin = false;
if (isset($_COOKIE["session"])) {
	$loggedin = true;
}

?>

<!-- js     -->
<link rel="stylesheet" type="text/css" href="style.css" />
<script>
function getFeed() {

	feedreq=new XMLHttpRequest()
	feedreq.onreadystatechange = function() {
		if ( this.readyState == 4 && this.status == 200 ) {

		let jsonResponse = JSON.parse(this.responseText)

		document.getElementById("placeholder").innerHTML=jsonResponse.toString()
		}
	}
	feedreq.open("GET","feedreq.php",true)
	feedreq.send()
}
</script>

<!-- header -->

<h5>under const</h5>

<!-- content -->

<? if (! $loggedin) { ?>
	please log in <a href="login.php">here</a>
<? } ?>
<button onclick="getFeed()">refresh</button>
<table><thead>
<tr>
	<td><b>Date</b></td>
	<td><b>Description</b></td>
	<td><b>Channel</b></td>
	<td align="right"><b>Star</b></td>
	<td align="right"><b>Hide</b></td>
</tr>
</thead><tbody>
<div id="placeholder"></div>
<tr>
	<td></td>
	<td id="bottomText" colspan="4">RSS-feed will be displayed here [...]</td>
</tr>
</tbody></table>

