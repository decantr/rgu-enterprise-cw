<table>
	<tr>
		<td><a href="/index.php"><img src="logo.png" alt="" width="32" height="32" /></a></td>
		<td>
			<h1>readss</h1><span class="desc">the snazziest of rss readers
			</span>
		</td>
	</tr>
	<tr class="url">
		<td></td>
		<td>git clone <a href="git://github.com/decantr/readss">git://github.com/decantr/readss</a></td>
	</tr>
	<tr>
		<td></td>
		<? if ( ! isset( $_COOKIE["session"] ) ) { ?>
				<td>&emsp;&emsp;please <a href="login.php">login</a> or <a href="register.php">register</a></td>
		<? } else { ?>
		<td>
			<a href="index.php">Feed</a> | <a href="subscribe.php">Refs</a> | <a href="file/README.html">README</a> | <a href="logout.php">Logout</a></td>
	</tr>
<? } ?>
</table>
<hr />
