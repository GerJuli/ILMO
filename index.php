<?php

/*
controller for a lending system
version 1.0
date 08.11.18
tested on php 7.2 and php 5.6.38
Database: MariaDB
 */


session_start();

//uncomment to show errors 
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//start: includes
include ("config/config.inc.php");
include ("class/class.php");
//$oObject = new Data();
//object: parameter to clear which object
$sName = "book";
if (isset ($_REQUEST['ac'])){
	$sName = substr($_REQUEST['ac'],0,4);
}
if($sName == 'user'){
	$oObject = new User;

}
elseif ($sName == 'book') {
	$oObject = new Book;
}
elseif ($sName == 'lend') {
	$oObject = new Lend;
}
elseif ((!isset ($oObject->r_ac)) or ($sName == 'logi') or ($sName == 'strt') or ($sName == 'logo')){
	$oObject = new Data;
}
//view header
$oObject->output = "";
$oObject->navigation = $oObject->get_view("views/navigation.php");
//methods
switch ($oObject->r_ac){
	case 'strt':
		$oObject->set_session();
		$oObject->output .=  $oObject->get_view("views/start.php");
			
		break;
	case 'logi':
		$oObject->output .=  $oObject->get_view('views/login_form.php');
		break;
	case 'logo':
		$oObject->output .=  $oObject->get_view('views/login_form.php');
		break;
		
	case 'book_new':
		if ($_SESSION['admin']==1){	
		$oObject->output .= $oObject->get_view('views/book_form.php');
		}
		else{
			echo "Keine Berechtigung";
		}
		break;
	
	case 'book_change':
		if ($_SESSION['admin']==1){	
		$oObject->aRow_all = $oObject->get_book();
		$oObject->aRow = $oObject->aRow_all[$oObject->r_book_ID];
		include ('views/book_form.php');
		}
		else{
			echo "Keine Berechtigung";
		}
		break;	
	case 'book_save':
		if ($_SESSION['admin']==1){	
		$oObject->save_book();
		$oObject->r_book_ID = NULL;
		$oObject->aBook = $oObject->get_book();
		$oObject->output .= $oObject->get_view("views/all_books.php");
		}
		else{
			echo "Keine Berechtigung";
		}
		break;
	case 'book_show':
		$oObject->aBook = $oObject->get_book();
		$oObject->output .= $oObject->get_view("views/all_books.php");
		break;
	case 'book_show_plain':
		$oObject->aBook = $oObject->get_book_plain();
		$oObject->output .= $oObject->get_view("views/all_books_plain.php");
		break;
	case 'book_delete':
		if ($_SESSION['admin']==1){	
		$oObject->delete_book();
		$oObject->r_book_ID = NULL;
		$oObject->aBook = $oObject->get_book();
		$oObject->output .= $oObject->get_view("views/all_books.php");
		}
		else{
			echo "Keine Berechtigung";
		}
		break;
	case 'user_new':
		if ($_SESSION['admin']==1){	
		$oObject->output .= $oObject->get_view("views/user_form.php");
		}
		else{
			echo "Keine Berechtigung";
		}
		break;
	case 'user_save':
		if (($_SESSION['admin']==1) or ($_SESSION['user_ID']==$oObject->r_user_ID)){	
			$er = $oObject->check_input();
			if ($er != ""){
				$oObject->output .= $er;
			}
			else{
			$oObject->save_user();
			$oObject->r_user_ID = NULL;
			$oObject->aUser = $oObject->get_user();
			$oObject->output .= $oObject->get_view("views/all_user.php");
			}
		}
		else{
			echo "Keine Berechtigung";
		}
		break;
	case 'user_delete':
		if (($_SESSION['admin']==1) or ($_SESSION['user_ID']==$oObject->r_user_ID)){	
		$oObject->delete_user();
		$oObject->r_user_ID = NULL;
		$oObject->aUser = $oObject->get_user();
		$oObject->output .= $oObject->get_view("views/all_user.php");
		}
		else{
			echo "Keine Berechtigung";
		}
		break;
	case 'user_self':
		$oObject->r_user_ID =$_SESSION['user_ID'];
		$oObject->aUser = $oObject->get_user();
		$oObject->output .= $oObject->get_view("views/all_user.php");
		break;
	case 'user_show':
		if ($_SESSION['admin']==1){	
		$oObject->aUser = $oObject->get_user();
		$oObject->output .= $oObject->get_view("views/all_user.php");
		}
		else{
			echo "Keine Berechtigung";
		}
		break;
	case 'user_search':	
		if ($_SESSION['admin']==1){	
		$oObject->output .= $oObject->get_view("views/user_search.php");
		}
		else {
			echo "Keine Berechtigung";
		}
		break;
	case 'user_change':
		if (($_SESSION['admin']==1) or ($_SESSION['user_ID']==$oObject->r_user_ID)){	
		$oObject->aRow_all = $oObject->get_user();
		$oObject->aRow = $oObject->aRow_all[$oObject->r_user_ID];
		$oObject->output .= $oObject->get_view("views/user_form.php");
		}
		else{
			echo "Keine Berechtigung";
		}
		break;
		
	case 'lend_new':
		if ($_SESSION['admin']==1){	
		$oObject->output .= $oObject->get_view("views/lend_form.php");
		}
		else{
			echo "Keine Berechtigung";
		}
		break;
	case 'lend_save':
		if ($_SESSION['admin']==1){	
			$error_message = $oObject->check_book_lend($oObject->r_book_ID);
			$error_message .= $oObject->check_input();
			if($error_message !=""){
				$oObject->output .= $error_message;
			}
			else{
				$oObject->save_lend();
				$oObject->r_lend_ID = NULL;
				$oObject->aLend = $oObject->get_lend();
				$oObject->output .= $oObject->get_view("views/all_lend.php");
			
			}
		}
		else{
			echo "Keine Berechtigung";
		}
		break;
	case 'lend_return':
		if ($_SESSION['admin']==1){	
		$oObject->return_lend();
	//	$oBook = new Book();
	//	$oBook->return_book($oObject->r_book_ID);
		$oObject->r_lend_ID = NULL;
		$oObject->r_book_ID = NULL;
		$oObject->aLend = $oObject->get_lend();
		$oObject->output .= $oObject->get_view("views/all_lend.php");
		}
		else{
			echo "Keine Berechtigung";
		}
		break;
	case 'lend_delete':
		if ($_SESSION['admin']==1){	
		$oObject->delete_lend();
	//	$oBook = new Book();
	//	$oBook->return_book($oObject->r_book_ID);
		$oObject->r_lend_ID = NULL;
		$oObject->aLend = $oObject->get_lend();
		$oObject->output .= $oObject->get_view("views/all_lend.php");
		}
		else{
			echo "Keine Berechtigung";
		}
		break;
	case 'lend_show':
		if (($_SESSION['admin']==1) or ($_SESSION['user_ID'] == $oObject->r_user_ID)){	
		$oObject->aLend = $oObject->get_lend();
		$oObject->output .= $oObject->get_view("views/all_lend.php");
		}
		else{
			echo "Keine Berechtigung";
		}
		break;
	case 'lend_self':
		$oObject->r_user_ID = $_SESSION['user_ID'];
		$oObject->aLend = $oObject->get_lend();
		$oObject->output .= $oObject->get_view("views/all_lend.php");
		break;
	case 'lend_change':
		if ($_SESSION['admin']==1){	
		$oObject->get_lend();
		$oObject->output .= $oObject->get_view("views/lend_form.php");
		}
		else{
			echo "Keine Berechtigung";
		}
		break;
	default: 
		$oObject->aBook = $oObject->get_book();
		$oObject->output .= $oObject->get_view("views/all_books.php");
		break;


}

//$oObject->show_this();
echo $oObject->get_view("views/head.php");
echo $oObject->get_view("views/body.php");

?>
