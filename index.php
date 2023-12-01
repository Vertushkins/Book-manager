<?php
	require_once 'login.php';
	
	$pdo = new PDO($attr, $user, $pass, $opts);
	
	//print_r($_POST);
	$warning = '';
	
	//'add book' form processing
	if (isset($_POST['add'])){
	
		if ($_POST['author'] != NULL &&
		    $_POST['title'] != NULL &&
		    $_POST['pages'] != NULL) {
		    
			$author = $pdo->quote($_POST['author']);
			$title = $pdo->quote($_POST['title']);
			$pages = $pdo->quote($_POST['pages']);
			$sql = "INSERT INTO books (author, title, pages) VALUES ($author, $title, $pages)";
			$pdo->query($sql);
		}
		else {
			$warning = 'Please do not leave empty fields :(';
		}
	}
	
	//'put a bookmark' form processing
	elseif (isset($_POST['edit'])){
		$id = $pdo->quote($_POST['id']);
		$bookmark = $pdo->quote($_POST['bookmark']);
		$last_view = $pdo->quote($_POST['last_view']);
		$sql = "UPDATE books SET bookmark=$bookmark, last_view=$last_view WHERE id=$id";
		$pdo->query($sql);
	}
	
	//delete button processing
	elseif (isset($_POST['delete'])){
		$id = $pdo->quote($_POST['id']);
		$sql = "DELETE FROM books WHERE id=$id";
		$pdo->query($sql);
	}
	
	//show 'add book' form
	echo <<< _END
	<form action="" method="post">
	<h2><b>➕ Add book</b></h2>
	<pre>
	<big>Author</big> <input type="text" name="author" placeholder="..."><br>
	<big>Title</big>  <input type="text" name="title" placeholder="..."><br>
	<big>Pages</big>  <input type="text" name="pages" placeholder="..."><br>
	<input type="submit" name="add" value="Click to add"> <big><font color="red">$warning</font color="red"></big><br>
	</pre>
	<hr>
	</form>
	_END;
	
	//show 'put a bookmark' form
	echo <<< _END
	<form action="" method="post">
	<h2><b>✍️ Put a bookmark</b></h2>
	<pre>
	<big>Number  </big>  <input type="text" name="id" placeholder="..."><br>
	<big>Bookmark</big>  <input type="text" name="bookmark" placeholder="..."><br>
	<big>Last view</big> <input type="text" name="last_view" placeholder="dd.mm.yyyy"><br>
	<input type="submit" name="edit" value="Click to edit"><br>
	</pre>
	<hr>
	</form>
	_END;
	
	//variables for searching books
	$order = 'id'; $where = ''; $like = '';
	
	//search button processing
	//and also radio buttons
	if (isset($_POST['find'])){
		if (isset($_POST['show'])){
			switch ($_POST['show']){
					
				case 'Finished books':
				$where = 'WHERE pages = bookmark';
				break;
					
				case 'Books in progress':
				$where = 'WHERE pages <> bookmark AND bookmark <> 0';
				break;
					
				case 'Planned books':
				$where = 'WHERE bookmark = 0';
				break;
			}
		}
		//search sql commad depends on 'WHERE' in radio buttons
		if ($_POST['find'] != NULL){
			$find = $_POST['find'];
			if ($where == '') {$like = "WHERE title LIKE '%$find%' OR author LIKE '%$find%'";}
			else {$like = " AND (title LIKE '%$find%' OR author LIKE '%$find%')";}
		}
	}

	$sql = "SELECT * FROM books " . $where . $like . " ORDER BY " . $order;
	//echo $sql;
	$result = $pdo->query($sql);
	
	//'library' form processing
	echo <<< _END
	<form action="" method="post">
	<h2><b>📚 Library</b></h2>
	<input type="search" name="find" placeholder="🔍 Write title or author" width="200p">
	<input type="submit" value="Search"><br><br>
	<b><big>Filtered by: </big></b>
	<input type="radio" name="show" value="All books">
	<label>All books</label>
	<input type="radio" name="show" value="Finished books">
	<label>Finished books</label>
	<input type="radio" name="show" value="Books in progress">
	<label>Books in progress</label>
	<input type="radio" name="show" value="Planned books">
	<label>Planned books</label>
	</form>
	_END;
	
	//show found records
	while ($book = $result->fetch()){
		$number = $book['id'];
		$author = $book['author'];
		$title = $book['title'];
		$pages = $book['pages'];
		$bookmark = $book['bookmark'];
		$last_view = $book['last_view'];
		
		//form for record 
		echo <<< _END
		<form action="" method="post">
		<pre>
		<big>Number: $number</big>
		<big>Author: $author</big>
		<big>Title: $title</big>
		<big>Pages: $pages</big>
		<big>Bookmark: $bookmark</big>
		<big>Last view: $last_view</big>
		<input type="submit" name="delete" value="Delete">
		<input type="hidden" name="id" value="$number">
			
		</pre>
		</form>
		_END;
	}
?>
