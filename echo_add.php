<?php
	function echo_add($warning){
		echo <<< _END
		<form action="" method="post">
		<h2><b>➕ Add book</b></h2>
		<pre>
		<big>Title<font color="red"><sup>*</sup></font></big>  <input type="text" name="title" placeholder="..."><br>
		<big>Author<font color="red"><sup>*</sup></font></big> <input type="text" name="author" placeholder="..."><br>
		<big>Pages<font color="red"><sup>*</sup></font></big>  <input type="text" name="pages" placeholder="..."><br>
		<big>Image</big>  <input type="text" name="image" placeholder="URL"><br>
		<input type="submit" name="add" value="Click to add"> <big><font color="red">$warning</font color="red"></big><br>
		</pre>
		<hr>
		</form>
		_END;
	}
?>
