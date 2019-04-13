const options = {year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit'}

function formatDate ( d ) {
	let t = new Date(Date.parse(d))
	if ( t == "Invalid Date" )
		t = new Date(Date.parse(
		d.replace ( /-/gi, '/' )
	))
	return t.toLocaleString('en-GB', options).replace( ',' , '' ).replace( /[\/]/gi , '-' )
}

function getTopArticles( n ) {
fetch( "/lib/Request.php?q=getTopArticles&n=" + n )
	.then( ( r ) => {
		return r.json()
	})
	.then( ( r ) => {
		displayArticles( r )
	})
}

function displayArticles( r ) {
	let str = ""
	for ( let i of r ) {
		let time = formatDate(i.pubDate)
		let title = i.title.slice(0, 60)
		let channel = i.channel.slice(0, 20)

		str += "" +
			"<tr><td>" + time + "</td>" +
			"<td><a href=" +i.link + ">" + title + "</a></td>" +
			"<td>" + channel + "</td>" +
			"<td align=\"right\">Hide</td></tr>"
	}
	document.getElementById("posttable").innerHTML=str
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

function displayFeeds( article ) {
	let str = ""
	for ( let i of article ) {
		let time = formatDate(i.updated)
		let title = i.title.slice(0, 60)
		let link = i.link
		str += "" +
			"<tr><td>" + time + "</td>" +
			"<td><a href=" + i.link + ">" + i.title + "</a></td>" +
			"<td align=\"right\">" + i.id + "</td>" +
			"<td align=\"right\">Star</td>" +
			"<td align=\"center\"><a href=\"#\" onclick=\"unsubscribe(" + i.id + ")\">-</a></td></tr>"
	}
	document.getElementById("posttable").innerHTML=str
}

function refreshArticles() {
	fetch( "/lib/Request.php?q=refreshArticles" )
	.then()
	.then( () => {
		getTopArticles()
	})
}

function unsubscribe( feed_id ) {
	fetch ( "/lib/Request.php?q=unsubscribe&feed_id=" + feed_id )
		.then()
		.then( () => {
			getSubscriptions()
		})
}

function showFeeds( str ) {
  if ( str.length > 0 )	fetch ( "lib/Request.php?q=search&s=" + str )
		.then( ( r ) => { return r.json() } )
		.then( ( r ) => {
			suggestions = ""
			for ( let i of r ) {
				suggestions += "" +
					"<a href=\"#\" onclick=\"choose(\'" + i.link + "\')\">" +  i.link + "</a><br />"
			}

			document.getElementById( "searchresults" ).innerHTML = suggestions
		})
}

function choose( selected ) {
	document.getElementById( "search" ).value = selected
}

