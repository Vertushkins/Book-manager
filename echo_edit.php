<?php
	function echo_edit($book){
		$id = $book['id'];
		$title = $book['title'];
		$author = $book['author'];
		$pages = $book['pages'];
		$image = $book['image'];
		$rating = $book['rating'];
		
		global $find, $status, $sort;
		
		echo <<< _END
		<form action="" method="post">
		<h2><b>✍️ Edit</b></h2>
		<pre>
		<big>Title<font color="red"><sup>*</sup></font></big>  <input type="text" name="title" value="$title" required><br>
		<big>Author<font color="red"><sup>*</sup></font></big> <input type="text" name="author" value="$author" required><br>
		<big>Pages<font color="red"><sup>*</sup></font></big>  <input type="number" min="1" max="100000" name="pages" value="$pages" required><br>
		<big>Rating</big>  <input type="number" min="0" max="5" name="rating" value="$rating"><br>
		<big>Image</big>   <input type="text" name="image" value="$image"><br>
		<input type="submit" name="edit" value="Click to edit"> <input type="submit" value="Undo" form="books"> <big><font color="red">* required fields</font color="red"></big>
		</pre>
		
		<input type="hidden" name="id" value="$id">
		<input type="hidden" name="find" value="$find">
		<input type="hidden" name="status" value="$status">
		<input type="hidden" name="sort" value="$sort">
		
		<hr>
		</form>
		_END;
	}
?>
