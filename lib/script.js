const options = {year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit'}

function formatDate ( d ) {
	return new Date(Date.parse(d))
		.toLocaleString('en-GB', options)
		.replace( ',' , '' )
		.replace( /[\/]/gi , '-' )
}

function getFeed() {

fetch( "../feedreq.php" )
	.then( (response) => {
		return response.json()
	})
	.then( (res) => {
		display(res)
	})

}

function display(content) {

	let str = "";

	for ( let i of content ) {
		// TODO : cleanup formatting functions
		let time = formatDate(i.published)
		let title = i.title.slice(0, 60)
		let feed = i.feed_id.slice(0, 20)

		str += "<tr><td>" + time +
			"</td><td><a href=" +i.link +
			">" + title + "</a></td><td>" +
			feed + "</td><td align=\"right\">" +
			"Star</td><td align=\"right\">Hide</td></tr>"
	}

	document.getElementById("posttable").innerHTML=str;

}
