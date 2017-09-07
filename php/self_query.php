<?php
if ($_POST['action'] == 'submitted') {
    echo '<pre>';

    print_r($_POST);
    echo '<a href="'. $_SERVER['PHP_SELF'] .'">Essayez à nouveau</a>';

    echo '</pre>';
} else {
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    Nom   :  <input type="text" name="personal[name]" /><br />
    Email : <input type="text" name="personal[email]" /><br />
    Biere : <br />
    <select multiple name="vin[]">
        <option value="bordeaux">bordeaux</option>
        <option value="beaujolais">beaujolais</option>
        <option value="loire">loire</option>
    </select><br />
    <input type="hidden" name="action" value="submitted" />
    <input type="submit" name="submit" value="submit me!" />
</form>
<?php
}
?>

