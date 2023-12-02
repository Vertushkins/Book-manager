<?php
	function echo_edit($warning, $book){
		$id = $book['id'];
		$title = $book['title'];
		$author = $book['author'];
		$pages = $book['pages'];
		$image = $book['image'];
		
		echo <<< _END
		<form action="" method="post">
		<h2><b>✍️ Edit</b></h2>
		<pre>
		<big>Title<font color="red"><sup>*</sup></font></big> <input type="text" name="title" value="$title"><br>
		<big>Author<font color="red"><sup>*</sup></font></big> <input type="text" name="author" value="$author"><br>
		<big>Pages<font color="red"><sup>*</sup></font></big> <input type="text" name="pages" value="$pages"><br>
		<big>Image</big> <input type="text" name="image" value="$image"><br>
		<input type="submit" name="edit" value="Click to edit"> <big><font color="red">$warning</font color="red"></big>
		<input type="hidden" name="id" value="$id"><br>
		</pre>
		<hr>
		</form>
		_END;
	}
?>
