const options = {year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit'}

function formatDate ( d ) {
	return d;
	//let t = new Date(Date.parse(d))
	//if ( t == "Invalid Date" )
	//	t = new Date(Date.parse(
//		d.replace ( /-/gi, '/' )
	//))
	//t.toLocaleString('en-GB', options).replace( ',' , '' ).replace( /[\/]/gi , '-' )
}

function getTopArticles() {

fetch( "/lib/Request.php?q=getTopArticles" )
	.then( (response) => {
		return response.json()
	})
	.then( (res) => {
		displayArticles(res)
	})

}

function displayArticles(content) {

	let str = "";
	for ( let feed of content ) {
	for ( let i of feed ) {
		// TODO : cleanup formatting functions
		let time = formatDate(i.published)
		console.log(i.title)
		let title = i.title.slice(0, 60)
		let feed = i.feed_id.slice(0, 20)

		str += "<tr><td>" + time +
			"</td><td><a href=" +i.link +
			">" + title + "</a></td><td>" +
			feed + "</td><td align=\"right\">" +
			"Star</td><td align=\"right\">Hide</td></tr>"
	}
	}
	document.getElementById("posttable").innerHTML=str;

}

function getSubscriptions() {

	fetch( "lib/Request.php?q=getSubscribedFeeds" )
	.then( (response) => {
		return response.json()
	})
	.then( (res) => {
		displayFeeds(res)
	})

}

function displayFeeds( content ) {

	let str = "";

	console.log(content)

	for ( let i of content ) {
		let time = formatDate(i.updated)
		let title = i.title.slice(0, 60)
		let link = i.link

		str += "" +
			"<tr><td>" + time + "</td>" +
			"<td><a href=" + i.link + ">" + i.title + "</a></td>" +
			"<td align=\"right\">" + i.id + "</td>" +
			"<td align=\"right\">Star</td>" +
			"<td align=\"right\">Hide</td></tr>"
	}

	document.getElementById("posttable").innerHTML=str;



}
