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
	require_once 'sanitize.php';
	
	$pdo = new PDO($attr, $user, $pass, $opts);
	
	//print_r($_POST);
	//print_r($GLOBALS);
	
	$find = str_replace("'","",sanitize($_POST['find']));
	$status = $_POST['status'];
	$sort = $_POST['sort'];
	
	//'add book' form processing
	if (isset($_POST['add'])){
		if ($_POST['add'] == 'Click to add'){
		
			$author = sanitize($_POST['author']);
			$title = sanitize($_POST['title']);
			$pages = sanitize($_POST['pages']);
			$rating = sanitize($_POST['rating']);
			$image = sanitize($_POST['image']);
			
			$sql = "INSERT INTO books (author, title, pages, image, rating) VALUES ($author, $title, $pages, $image, $rating)";
			$pdo->query($sql);

		}
		else echo_add();
	}
	
	//'bookmark' form processing
	if (isset($_POST['bookmark'])){
		$id = sanitize($_POST['id']);
		$sql = "SELECT * FROM books WHERE id=$id";
		$book = ($pdo->query($sql))->fetch();
		
		if ($_POST['bookmark'] == 'Put a bookmark'){
				
			$page_bookmark = sanitize($_POST['page_bookmark']);
			$last_view = sanitize($_POST['last_view']);
			$sql = "UPDATE books SET bookmark=$page_bookmark, last_view=$last_view WHERE id=$id";
			$pdo->query($sql);

		}
		else echo_bookmark($book);
	}
	
	//'edit' form processing
	if (isset($_POST['edit'])){
		$id = sanitize($_POST['id']);
		$sql = "SELECT * FROM books WHERE id=$id";
		$book = ($pdo->query($sql))->fetch();
		
		if ($_POST['edit'] == 'Click to edit'){
				
			$author = sanitize($_POST['author']);
			$title = sanitize($_POST['title']);
			$pages = sanitize($_POST['pages']);
			$rating = sanitize($_POST['rating']);
			$image = sanitize($_POST['image']);
			
			$sql = "UPDATE books SET author=$author, title=$title, pages=$pages, image=$image, rating=$rating WHERE id=$id";
			$pdo->query($sql);
		}
		else echo_edit($book);
	}
	
	//delete button processing
	if (isset($_POST['delete'])){
		$id = sanitize($_POST['id']);
		$sql = "DELETE FROM books WHERE id=$id";
		$pdo->query($sql);
	}
	
	//search button processing
	//and also radio buttons
	switch ($status){
		
		case 'Finished books':
		global $where, $finished_books;
		$where = 'WHERE pages = bookmark';
		$finished_books = 'checked';
		break;
		
		case 'Books in progress':
		global $where, $books_in_progress;
		$where = 'WHERE pages <> bookmark AND bookmark <> 0';
		$books_in_progress = 'checked';
		break;
			
		case 'Planned books':
		global $where, $planned_books;
		$where = 'WHERE bookmark = 0';
		$planned_books = 'checked';
		break;
		
		default:
		global $where, $all_books;
		$where = '';
		$all_books = 'checked';
	}
	
	switch ($sort){
		
		case 'title':
		global $order, $title_sort;
		$order = 'title';
		$title_sort = 'checked';
		break;
		
		case 'rating':
		global $order, $rating_sort;
		$order = 'rating DESC';
		$rating_sort = 'checked';
		break;
			
		case 'progress':
		global $order, $progress_sort;
		$order = 'progress DESC';
		$progress_sort = 'checked';
		break;
		
		default:
		global $order, $last_view_sort;
		$order = 'last_view';
		$last_view_sort = 'checked';
	}
	
	//search sql commad depends on 'WHERE' in radio buttons
	if ($find != NULL){
		if ($where == ''){
			global $like;
			$like = "WHERE title LIKE '%$find%' OR author LIKE '%$find%'";
		}
		else {
			global $like;
			$like = " AND (title LIKE '%$find%' OR author LIKE '%$find%')";
		}
	}
	

	$sql = "SELECT * FROM books " . $where . $like . " ORDER BY " . $order;
	//echo $sql;
	$result = $pdo->query($sql);
	
	//'books' form processing
	echo <<< _END
	<form action="" method="post" id="books">
		<h2><b>📚 Books</b></h2>
			<input type="search" name="find" placeholder="🔍 Write title or author" value="$find">
			<input type="submit" value="Search">
			<input type="submit" name="add" value="➕"><br><br>
			
		<b><big>Status:</big></b>&nbsp
			<label><input type="radio" name="status" value="All books" $all_books> All books</label>
			<label><input type="radio" name="status" value="Finished books" $finished_books> Finished books</label>
			<label><input type="radio" name="status" value="Books in progress" $books_in_progress> Books in progress</label>
			<label><input type="radio" name="status" value="Planned books" $planned_books> Planned books</label><br>
			
		<b><big>Sort by:</big></b>
			<label><input type="radio" name="sort" value="last_view" $last_view_sort> Last view</label>
			<label><input type="radio" name="sort" value="title" $title_sort> Title</label>
			<label><input type="radio" name="sort" value="rating" $rating_sort> Rating</label>
			<label><input type="radio" name="sort" value="progress" $progress_sort> Progress</label><br>
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
		$rating = "<big><big>" . str_repeat("★",$book['rating']) . str_repeat("☆",5 - $book['rating']) . "</big></big>";
		
		//default image or not
		if ($book['image'] != '') $image = $book['image'];
		else $image = 'https://dl.acm.org/specs/products/acm/releasedAssets/images/cover-default--book-eb4a10bc8384c82eedc8ec474d7dd09a.svg';
		
		//form for record 
		echo <<< _END
		<form action="" method="post">
		<pre>
		<img src="$image" width = "150px">
		  <progress max="$pages" value="$bookmark"></progress>
		<big>Rating:$rating</big>
		<big>Title: $title</big>
		<big>Author: $author</big>
		<big>Last view: $last_view</big>
		</pre>
		<input type="submit" name="bookmark" value="Bookmark">
		<input type="submit" name="edit" value="Edit">
		<input type="submit" name="delete" value="Delete">
		
		<input type="hidden" name="id" value="$id">
		<input type="hidden" name="find" value="$find">
		<input type="hidden" name="status" value="$status">
		<input type="hidden" name="sort" value="$sort">
		</form>
		_END;
	}
?>
