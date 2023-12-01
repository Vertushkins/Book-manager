<?php
	require_once 'login.php';
	
	$pdo = new PDO($attr, $user, $pass, $opts);
	
	echo <<< _END
	<form action="" method="post">
	<h2><b>Add book</b></h2>
	<pre>
	<big>Author</big> <input type="text" name="author" placeholder="..."><br>
	<big>Title</big>  <input type="text" name="title" placeholder="..."><br>
	<big>Pages</big>  <input type="text" name="pages" placeholder="..."><br>
	<input type="submit" name="add" value="Click to add"><br>
	</pre>
	</form>
	_END;
	
	echo <<< _END
	<form action="" method="post">
	<h2><b>Edit book</b></h2>
	<pre>
	<big>Number  </big>  <input type="text" name="id" placeholder="..."><br>
	<big>Bookmark</big>  <input type="text" name="bookmark" placeholder="..."><br>
	<big>Last view</big> <input type="text" name="last_view" placeholder="dd.mm.yyyy"><br>
	<input type="submit" name="edit" value="Click to edit"><br>
	</pre>
	</form>
	_END;
	
	echo <<< _END
	<form action="" method="post">
	<h2><b>Library</b></h2>
	<input type="submit" name="show" value="All books">
	<input type="submit" name="show" value="Finished books">
	<input type="submit" name="show" value="Books in progress">
	<input type="submit" name="show" value="Planned books">
	</form>
	_END;
	
	//print_r($_POST);
	
	if (isset($_POST['add'])){
		$author = $pdo->quote($_POST['author']);
		$title = $pdo->quote($_POST['title']);
		$pages = $pdo->quote($_POST['pages']);
		$sql = "INSERT INTO books (author, title, pages) VALUES ($author, $title, $pages)";
		$pdo->query($sql);
	}
	
	elseif (isset($_POST['edit'])){
		$id = $pdo->quote($_POST['id']);
		$bookmark = $pdo->quote($_POST['bookmark']);
		$last_view = $pdo->quote($_POST['last_view']);
		$sql = "UPDATE books SET bookmark=$bookmark, last_view=$last_view WHERE id=$id";
		$pdo->query($sql);
	}
	
	elseif (isset($_POST['show'])){
		$order = ''; $where = '';
		
		switch ($_POST['show']){
		
			case 'All books':
			$order = 'id';
			break;
			
			case 'Finished books':
			$order = 'id';
			$where = 'WHERE pages = bookmark';
			break;
			
			case 'Books in progress':
			$order = 'id';
			$where = 'WHERE pages <> bookmark AND bookmark <> 0';
			break;
			
			case 'Planned books':
			$order = 'id';
			$where = 'WHERE bookmark = 0';
			break;
		}
		
		$sql = "SELECT * FROM books " . $where . " ORDER BY " . $order;
		$result = $pdo->query($sql);
		
		while ($book = $result->fetch()){
			$number = $book['id'];
			$author = $book['author'];
			$title = $book['title'];
			$pages = $book['pages'];
			$bookmark = $book['bookmark'];
			$last_view = $book['last_view'];
			
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
	}
	
	elseif (isset($_POST['delete'])){
		$id = $pdo->quote($_POST['id']);
		$sql = "DELETE FROM books WHERE id=$id";
		$pdo->query($sql);
	}
?>
