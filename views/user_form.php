

	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	<input type = hidden name="ac" value = "user_save">
	<input type = hidden name="user_ID" value = "<?php echo $oObject->r_user_ID; ?>">
	Vorname: <input type="text" name="forename" value="<?php if(isset($oObject->aRow['forename'])){echo $oObject->aRow['forename'];} ?>"><br>
	Nachname: <input type="text" name="surname" value="<?php if(isset($oObject->aRow['surname'])){echo $oObject->aRow['surname'];} ?>"> <br>
	E-Mail Adresse: <input type="text" name="email" value="<?php if(isset($oObject->aRow['email'])){echo $oObject->aRow['email'];}?>"> <br>
		<input type="submit" value="absenden">
		<input type="reset" value="Zurücksetzen">	
</form>

