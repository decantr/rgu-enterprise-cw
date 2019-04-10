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

function display(content) {

	let str = "";
	let options = {year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit'};

	for ( let i of content ) {
		let time = new Date(Date.parse(i.date))
		time = time.toLocaleString('en-GB', options)
		let summary = i.desc.replace(/<(?:.|\n)*?>/gm, '').slice(0, 20) + "..."
		let title = i.title.replace(/<(?:.|\n)*?>/gm, '').slice(0, 64)

		str += "<tr><td>" + time +
			"</td><td><a href=" +i.link +
			">" + title + "</a></td><td>" +
			summary + "</td><td align=\"right\">" +
			"Star</td><td align=\"right\">Hide</td></tr>"
	}

	document.getElementById("posttable").innerHTML=str;

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

