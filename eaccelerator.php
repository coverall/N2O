<?php
if (isset($_POST['clear']))
{
	eaccelerator_clear();
}
else if (isset($_POST['clean']))
{
	eaccelerator_clean();
}
else if (isset($_POST['purge']))
{
	eaccelerator_purge();
}

?>

<?php virtual('/retrix_global/header.php'); ?>

<style type="text/css">
table.retrix
{
	border-collapse: collapse;
}

table.retrix th
{
	text-align: left;
	background-color: #ddd;
	color: #333;
	padding: 3px;
}

table.retrix td
{
	font-size: smaller;
	color: #555;
	border: 1px solid #ddd;
	padding: 3px;
}
</style>

<table border="0" cellspacing="0" class="retrix">
 <tr>
  <th style="text-align:left">File</th>
  <th>Reloads</th>
  <th style="text-align:right">Size</th>
 </tr>
 
<?php
if (true) { //(function_exists("eaccelerator_cached_scripts")) {
  $scripts = eaccelerator_cached_scripts();
  
  foreach ($scripts as $script)
  {
?>
 <tr>
  <td><?php echo $script['file']; ?></td>
  <td><?php echo $script['reloads']; ?></td>
  <td align="right"><?php echo number_format($script['size']); ?></td>
 </tr>

<?php
  }
  
  
} else {
  echo "<html><head><title>eAccelerator</title></head><body><h1 align=\"center\">eAccelerator is not installed</h1></body></html>";
}
?>
</table>

<form method="POST">
 <input type="submit" name="clear" value="Clear Cache">
 <input type="submit" name="clean" value="Clean Cache">
 <input type="submit" name="purge" value="Purge Cache">
</form>

<?php virtual('/retrix_global/footer.php'); ?>
