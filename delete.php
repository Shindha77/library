<?php

$titre = 'Supprimer un livre';

require 'header.php';

$bookId = $_GET['id'];

if (isset($_POST['validate'])) {

    $queryDelete = $pdo->prepare("DELETE FROM livre WHERE id =:bookId");

    $queryDelete->bindValue(":bookId", $bookId, PDO::PARAM_INT);

    $queryDelete->execute();

    header('location: index.php');
}
?>

<p>|</p>
<h1 class="m-top">Supprimer un livre</h1>
<p>Êtes-vous sûr de vouloir supprimer définitivement ce livre ?</p>
<div class="d-flex">
    <span><button class="button"><a href="index.php">NON</a></button></span>
    <form action="?id=<?= $bookId ?>" method="post">
        <span><button class="button" type="submit" name="validate" value="1">OUI</button></span>
    </form>
</div>