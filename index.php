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

fetch( "feedreq.php" )
	.then( (response) => {
		return response.json()
	})
	.then( (res) => {
		display(res)
	})

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
</thead><tbody id="posttable" >
<tr>
	<td></td>
	<td id="bottomText" colspan="4">RSS-feed will be displayed here [...]</td>
</tr>
</tbody></table>

