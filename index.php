<?php
/*
	For buttons in "Books":
	1. name="add" value="+"
	2. name="bookmark" value="Bookmark"
	3. name="edit" value="Edit"
	4. name="delete" value="Delete"
	
	For buttons in special sections
	1. name="add" value="Click to add"
	2. name="bookmark" value="Put a bookmark"
	3. name="edit" value="Click to edit"
*/	
	
	
	require_once 'login.php';
	require_once 'echo_add.php';
	require_once 'echo_bookmark.php';
	require_once 'echo_edit.php';
	
	$pdo = new PDO($attr, $user, $pass, $opts);
	
	//print_r($_POST);
	
	$warning = 'Please do not leave fields with * empty :(';
	$find = $_POST['find'];
	
	//'add book' form processing
	if (isset($_POST['add'])){
		if ($_POST['add'] == 'Click to add'){
		
			if ($_POST['author'] != NULL &&
			    $_POST['title'] != NULL &&
			    $_POST['pages'] != NULL) {
			    
				$author = $pdo->quote($_POST['author']);
				$title = $pdo->quote($_POST['title']);
				$pages = $pdo->quote($_POST['pages']);
				$image = $pdo->quote($_POST['image']);
				$sql = "INSERT INTO books (author, title, pages, image) VALUES ($author, $title, $pages, $image)";
				$pdo->query($sql);
			}
			
			else echo_add($warning);
		}
		
		else echo_add('');
	}
	
	//'bookmark' form processing
	if (isset($_POST['bookmark'])){
		$id = $_POST['id'];
		$sql = "SELECT * FROM books WHERE id=$id";
		$book = ($pdo->query($sql))->fetch();
		
		if ($_POST['bookmark'] == 'Put a bookmark'){
			
			if ($_POST['bookmark'] != NULL &&
			    $_POST['last_view'] != NULL) {
				
				$bookmark = $pdo->quote($_POST['bookmark']);
				$last_view = $pdo->quote($_POST['last_view']);
				$sql = "UPDATE books SET bookmark=$bookmark, last_view=$last_view WHERE id=$id";
				$pdo->query($sql);
			}
			
			else echo_bookmark($warning, $book);
		}
		
		else echo_bookmark('', $book);
	}
	
	//'edit' form processing
	if (isset($_POST['edit'])){
		$id = $_POST['id'];
		$sql = "SELECT * FROM books WHERE id=$id";
		$book = ($pdo->query($sql))->fetch();
		
		if ($_POST['edit'] == 'Click to edit'){
			
			if ($_POST['author'] != NULL &&
			    $_POST['title'] != NULL &&
			    $_POST['pages'] != NULL) {
				
				$author = $pdo->quote($_POST['author']);
				$title = $pdo->quote($_POST['title']);
				$pages = $pdo->quote($_POST['pages']);
				$image = $pdo->quote($_POST['image']);
				$sql = "UPDATE books SET author=$author, title=$title, pages=$pages, image=$image WHERE id=$id";
				$pdo->query($sql);
			}
			
			else echo_edit($warning, $book);
		}
		
		else echo_edit('', $book);
	}
	
	//delete button processing
	if (isset($_POST['delete'])){
		$id = $pdo->quote($_POST['id']);
		$sql = "DELETE FROM books WHERE id=$id";
		$pdo->query($sql);
	}
	
	//variables for searching books
	$order = 'id'; $where = ''; $like = '';
	
	//search button processing
	//and also radio buttons
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
		if ($where == '') $like = "WHERE title LIKE '%$find%' OR author LIKE '%$find%'";
		else $like = " AND (title LIKE '%$find%' OR author LIKE '%$find%')";
	}
	

	$sql = "SELECT * FROM books " . $where . $like . " ORDER BY " . $order;
	//echo $sql;
	$result = $pdo->query($sql);
	
	//'library' form processing
	echo <<< _END
	<form action="" method="post">
	<h2><b>📚 Books</b></h2>
	<input type="search" name="find" placeholder="🔍 Write title or author" value="$find">
	<input type="submit" value="Search">
	<input type="submit" name="add" value="➕"><br><br>
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
		$id = $book['id'];
		$author = $book['author'];
		$title = $book['title'];
		$pages = $book['pages'];
		$bookmark = $book['bookmark'];
		$last_view = $book['last_view'];
		$image = $book['image'];
		
		//form for record 
		echo <<< _END
		<form action="" method="post">
		<pre>
		<img src="$image" width = "150px">
		<big>Title: $title</big>
		<big>Author: $author</big>
		<big>Pages: $pages</big>
		<big>Bookmark: $bookmark</big>
		<big>Last view: $last_view</big>
		</pre>
		<input type="submit" name="bookmark" value="Bookmark">
		<input type="submit" name="edit" value="Edit">
		<input type="submit" name="delete" value="Delete">
		
		<input type="hidden" name="id" value="$id">
		<input type="hidden" name="find" value="$find">
		</form>
		_END;
	}
?>
