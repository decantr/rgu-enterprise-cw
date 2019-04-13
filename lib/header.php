<table>
	<tr>
		<td><a href="/index.php"><img src="logo.png" alt="" width="32" height="32" /></a></td>
		<td>
			<h1>readss</h1><span class="desc">
			<? if ( isset( $_SESSION["username"] )) { ?>
				welcome back <? echo $_SESSION["username"] ?>
			<? } else { ?>
				the simplest of rss readers
			<? } ?>
			</span>
		</td>
	</tr>
	<tr class="url">
		<td></td>
		<td>git clone <a href="git://github.com/decantr/readss">git://github.com/decantr/readss</a></td>
	</tr>
		<tr>
		<td></td>
		<? if ( ! isset( $_SESSION["token"] ) ) { ?>
				<td>&emsp;&emsp;please <a href="/user/login.php">login</a> or <a href="/user/register.php">register</a></td>
		<? } else { ?>
		<td>
			<a href="index.php">Feed</a> | <a href="manage.php">Manage</a> | <a href="https://github.com/decantr/readss/blob/master/README.md">README</a> | <a href="/user/logout.php">LOGOUT</a></td>
	</tr>
<? } ?>
</table>
<hr />
