<?php

require_once '_connec.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title><?php echo $titre ?></title>
</head>


<body>
    <nav>
        <ul class="d-flex-nav border">
            <li><a href="index.php"> liste des livres</a></li>
            <li><a href="add.php">ajouter un livre</a></li>
            <li>
                <form class="d-flex" action="" method="post">
                    <div>
                        <label for="request"></label>
                        <input class="input" type="text" id="request" name="request" placeholder="titre, auteur">
                    </div>
                    <div>
                        <button class="button" type="submit" name="search">Rechercher</button>
                    </div>
                </form>
            </li>
        </ul>
    </nav>
    <main>