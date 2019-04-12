<? session_start();

$loggedin = false;
if (isset($_SESSION["token"])) {
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
		// TODO : cleanup formatting functions
		let time = new Date(Date.parse(i.published))
		time = time.toLocaleString('en-GB', options)
		time = time.replace( ',' , '')
		time = time.replace( '/' , '-' )
		let title = i.title.replace(/<(?:.|\n)*?>/gm, '').slice(0, 60)
		let source = i.feed_id.replace(/<(?:.|\n)*?>/gm, '').slice(0, 20)

		str += "<tr><td>" + time +
			"</td><td><a href=" +i.link +
			">" + title + "</a></td><td>" +
			source + "</td><td align=\"right\">" +
			"Star</td><td align=\"right\">Hide</td></tr>"
	}

	document.getElementById("posttable").innerHTML=str;

}
</script>

<!-- header -->

<? include("header.php")?>

<!-- content -->
<body onLoad="getFeed()">
<table><thead>
<tr>
	<td><b>Date</b></td>
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
