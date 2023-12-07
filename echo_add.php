<?php
	function echo_add(){
	
		global $find, $status, $sort;
	
		echo <<< _END
		<form action="" method="post">
		<h2><b>➕ Add book</b></h2>
		<pre>
		<big>Title<font color="red"><sup>*</sup></font></big>  <input type="text" name="title" placeholder="The Captain's Daughter" required><br>
		<big>Author<font color="red"><sup>*</sup></font></big> <input type="text" name="author" placeholder="Pushkin A.S." required><br>
		<big>Pages<font color="red"><sup>*</sup></font></big>  <input type="number" min="1" max="100000" name="pages" placeholder="320" required><br>
		<big>Rating</big>  <input type="number" min="0" max="5" name="rating" placeholder="0-5"><br>
		<big>Image</big>   <input type="text" name="image" placeholder="URL"><br>
		<input type="submit" name="add" value="Click to add"> <input type="submit" value="Undo" form="books"> <big><font color="red">* required fields</font color="red"></big><br>
		</pre>
		
		<input type="hidden" name="find" value="$find">
		<input type="hidden" name="status" value="$status">
		<input type="hidden" name="sort" value="$sort">
		
		<hr>
		</form>
		_END;
	}
?>
