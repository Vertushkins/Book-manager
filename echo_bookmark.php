<?php
	function echo_bookmark($warning, $book){
		$id = $book['id'];
		$title = $book['title'];
		$author = $book['author'];
		
		echo <<< _END
		<form action="" method="post">
		<h2><b>🔖 Bookmark</b></h2>
		<font color="gray">Title: $title</font><br>
		<font color="gray">Author: $author</font><br>
		<pre>
		<big>Page mark<font color="red"><sup>*</sup></font></big> <input type="text" name="Page bookmark" placeholder="..."><br>
		<big>Last view<font color="red"><sup>*</sup></font></big> <input type="text" name="last_view" placeholder="dd.mm.yyyy"><br>
		<input type="submit" name="bookmark" value="Put a bookmark"> <big><font color="red">$warning</font color="red"></big>
		<input type="hidden" name="id" value="$id"><br>
		</pre>
		<hr>
		</form>
		_END;
	}
?>
