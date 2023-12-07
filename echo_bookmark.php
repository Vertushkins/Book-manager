<?php
	function echo_bookmark($book){
		$id = $book['id'];
		$title = $book['title'];
		$author = $book['author'];
		$pages = $book['pages'];
		$date = date('d.m.Y');
		$bookmark = $book['bookmark'];
		
		global $find, $status, $sort;
		
		echo <<< _END
		<form action="" method="post">
		<h2><b>🔖 Bookmark</b></h2>
		<font color="gray">Title: $title</font><br>
		<font color="gray">Author: $author</font><br>
		<pre>
		<big>Page mark<font color="red"><sup>*</sup></font></big> <input type="number" min="0" max="$pages" name="page_bookmark" placeholder="0-$pages  (last mark: $bookmark)" required><br>
		<big>Last view<font color="red"><sup>*</sup></font></big> <input type="text" name="last_view" value="$date" placeholder="dd.mm.yyyy" required><br>
		<input type="submit" name="bookmark" value="Put a bookmark"> <input type="submit" value="Undo" form="books"> <big><font color="red">* required fields</font color="red"></big>
		<input type="hidden" name="id" value="$id"><br>
		</pre>
		
		<input type="hidden" name="find" value="$find">
		<input type="hidden" name="status" value="$status">
		<input type="hidden" name="sort" value="$sort">
		
		<hr>
		</form>
		_END;
	}
?>
