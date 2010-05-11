<?

require('./lib/Tyrant.php');

$tt = Tyrant::connect('localhost', 1978);
if ($_GET['d'])
{
    if ($_POST['submit'])
    {
        $tt->out($_GET['d']);
        header('Location: '.$_SERVER['SCRIPT_NAME']);
    }
?>
<form method="post" class="deleting">
  <fieldset>
  <legend>Deleting <? echo $_GET['d']; ?></legend>
  <ul>
<?
    $data = json_decode($tt->get($_GET['d']), 1);
    foreach($data as $k => $v)
    {
?>
    <li>
      <label for="<? echo $k; ?>"><?
        echo ucfirst(str_replace('_', ' ', $k));
      ?>:</label>
      <input type="text" name="<? echo $k; ?>" value="<? echo $v; ?>" disabled="disabled" />
    </li>
<?
    }
?>
  </ul>
  </fieldset>
  <input type="submit" name="submit" value="confirm" />
</form>
<?
}

if ($_GET['e'])
{
    if ($_POST['submit'])
    {
        $data = Array();

        foreach ($_POST as $k => $v)
        {
            if ($k != 'submit')
            {
                $data[$k] = $v;
            }
        }

        $tmp_data = json_encode($data);
        $tt->put($_GET['e'], $tmp_data);
    }
?>
<form method="post" class="editing">
  <fieldset>
  <legend>Editing <? echo $_GET['e']; ?></legend>
  <ul>
<?
    $data = json_decode($tt->get($_GET['e']), 1);
    foreach($data as $k => $v)
    {
?>
    <li>
      <label for="<? echo $k; ?>"><?
        echo ucfirst(str_replace('_', ' ', $k));
      ?>:</label>
      <input type="text" name="<? echo $k; ?>" value="<? echo $v; ?>" />
    </li>
<?
    }
?>
  </ul>
  </fieldset>
  <input type="submit" name="submit" value="edit" />
</form>
<?
}


$first_element = 1;

?>
<table border="1">
<?
$tt->iterinit();
while ($key = $tt->iternext())
{
    $data = json_decode($tt->get($key), 1);

    if ($first_element == 1)
    {
?>
  <tr>
    <th>Key</th>
<?
        foreach($data as $k => $v)
        {
?>
    <th><? echo ucfirst(str_replace('_', ' ',$k)); ?></th>
<?
        }
?>
    <th>Actions</th>
  </tr>
<?
        $first_element = 0;
    }
?>
  <tr>
    <td><? echo $key; ?></td>
<?
    foreach($data as $k => $v)
    {
?>
    <td><? echo $v; ?></td>
<?
    }
?>
    <td>
      <a href="?d=<? echo $key; ?>">Delete</a>
      <a href="?e=<? echo $key; ?>">Edit</a>
    </td>
  </tr>
<?
}
?>
</table>

