<?php

$titre = 'Ajouter un livre';

require 'header.php';

if (isset($_POST['validate'])) {
    if (!empty($_POST['authorFirstname'])) {
        $authorFirstname = $_POST['authorFirstname'];
    } else {
        $authorFirstnameErr = "* veuillez entrer le prénom de l'auteur";
    }
    if (!empty($_POST['authorLastname'])) {
        $authorLastname = $_POST['authorLastname'];
    } else {
        $authorLastnameErr = "* veuillez entrer le nom de l'auteur";
    }
    if (!empty($_POST['title'])) {
        $title = $_POST['title'];
    } else {
        $titleErr = "* veuillez entrer le titre";
    }
    if (!empty($_POST['parution'])) {
        $parution = $_POST['parution'];
    } else {
        $parutionErr = "* veuillez entrer la date de parution";
    }
    if (!empty($_POST['edition'])) {
        $edition = $_POST['edition'];
    } else {
        $editionErr = "* veuillez entrer l'éditeur";
    }
    if (!empty($_POST['resume'])) {
        $resume = $_POST['resume'];
    } else {
        $resumeErr = "* veuillez entrer un résumé";
    }
    if (!empty($_POST['price']) && is_numeric($_POST['price'])) {
        $prix = $_POST['price'];
    } elseif (empty($_POST['price'])) {
        $priceErr = "* veuillez entrer un prix";
    } elseif (is_numeric($_POST['price']) == false) {
        $priceErr = "* veuillez n'entrer que des chiifres";
    }
    if (!empty($_POST['genre'])) {
        $genre = $_POST['genre'];
    } else {
        $genreErr = "* veuillez entrer un genre";
    }

    if (
        !isset($authorFirstnameErr) && !isset($authorLastnameErr) && !isset($titleErr) && !isset($parutionErr)
        && !isset($editionErr) && !isset($resumeErr) && !isset($priceErr) && !isset($genreErr)
    ) {

        $authorCheck = $pdo->prepare("SELECT id FROM auteur WHERE nom =:authorLastname AND prenom =:authorFirstname");

        $authorCheck->bindValue(":authorFirstname", $authorFirstname, PDO::PARAM_STR);
        $authorCheck->bindValue(":authorLastname", $authorLastname, PDO::PARAM_STR);

        $authorCheck->execute();

        if ($authorCheck->rowCount() != 0) {
            $authorIdArray = $authorCheck->fetchAll(PDO::FETCH_OBJ);

            foreach ($authorIdArray as $authorId) :
                $authorIdNb = $authorId->id;
            endforeach;

            $auteur_id = $authorIdNb;
        } else {
            $queryCreateAuthor = $pdo->prepare("INSERT INTO auteur (prenom, nom) VALUES (:authorFirstname, :authorLastname)");

            $queryCreateAuthor->bindValue(":authorFirstname", $authorFirstname, PDO::PARAM_STR);
            $queryCreateAuthor->bindValue(":authorLastname", $authorLastname, PDO::PARAM_STR);

            $queryCreateAuthor->execute();

            $auteur_id = $pdo->lastInsertId();
        }


        $genreCheck = $pdo->prepare("SELECT id FROM genre WHERE genre_nom =:genre");

        $genreCheck->bindValue(":genre", $genre, PDO::PARAM_STR);

        $genreCheck->execute();

        if ($genreCheck->rowCount() != 0) {
            $genreIdArray = $genreCheck->fetchAll(PDO::FETCH_OBJ);

            foreach ($genreIdArray as $genreId) :
                $genreIdNb = $genreId->id;
            endforeach;

            $genre_id = $genreIdNb;
        } else {
            $queryCreateGenre = $pdo->prepare("INSERT INTO genre (genre_nom) VALUES (:genre)");

            $queryCreateGenre->bindValue(":genre", $genre, PDO::PARAM_STR);

            $queryCreateGenre->execute();

            $genre_id = $pdo->lastInsertId();
        }

        $queryInsert = $pdo->prepare("INSERT INTO livre (`titre`, `publication` ,`edition`, `resume`, `prix`, `auteur_id`, `genre_id`) VALUES (:title, :parution, :edition, :resume, :prix, :auteur_id, $genre_id)");

        $queryInsert->bindValue(":auteur_id", $auteur_id, PDO::PARAM_INT);
        $queryInsert->bindValue(":title", $title, PDO::PARAM_STR);
        $queryInsert->bindValue(":parution", $parution, PDO::PARAM_STR);
        $queryInsert->bindValue(":edition", $edition, PDO::PARAM_STR);
        $queryInsert->bindValue(":resume", $resume, PDO::PARAM_STR);
        $queryInsert->bindValue(":prix", $prix, PDO::PARAM_STR);

        $queryInsert->execute();

        header('location: index.php');
    }
}

?>

<p>|</p>
<h1 class="m-top">Ajouter un livre</h1>
<form class="form" action="" method="post">
    <div>
        <label for="title">Titre :</label>
        <input type="text" id="title" name="title" value="<?php if (isset($title)) echo $title ?>">
        <span class="error"><?php if(isset($titleErr)) echo $titleErr ?></span>
    </div>
    <div>
        <label for="authorFirstname">Auteur :</label>
        <input type="text" id="authorFirstname" name="authorFirstname" placeholder="Prénom :" value="<?php if (isset($authorFirstname)) echo $authorFirstname ?>">
        <span class="error"><?php if(isset($authorFirstnameErr)) echo $authorFirstnameErr ?></span>
    </div>
    <div>
        <label for="authorLastname"></label>
        <input type="text" id="authorLastname" name="authorLastname" placeholder="Nom :" value="<?php if (isset($authorLastname)) echo $authorLastname ?>">
        <span class="error"><?php if(isset($authorLastnameErr)) echo $authorLastnameErr ?></span>

    </div>
    <div>
        <label for="parution">Publication :</label>
        <input type="date" id="parution" name="parution" value="<?php if (isset($parution)) echo $parution ?>" min="1455-02-23" max="<?= date('Y-m-d'); ?>">
        <span class="error"><?php if(isset($parutionErr)) echo $parutionErr ?></span>
    </div>
    <div>
        <label for="edition">Editeur :</label>
        <input type="text" id="edition" name="edition" value="<?php if (isset($edition)) echo $edition ?>">
        <span class="error"><?php if(isset($editionErr)) echo $editionErr ?></span>
    </div>
    <div>
        <label for="resume">Résumé :</label>
        <textarea id="resume" name="resume" placeholder="1000 caractères max"><?php if (isset($resume)) echo $resume ?></textarea>
        <span class="error"><?php if(isset($resumeErr)) echo $resumeErr ?></span>
    </div>
    <div>
        <label for="price">Prix :</label>
        <input type="text" id="price" name="price" placeholder="en €" value="<?php if (isset($prix)) echo $prix ?>">
        <span class="error"><?php if(isset($priceErr)) echo $priceErr ?></span>
    </div>
    <div>
        <label for="genre">Genre :</label>
        <input type="text" id="genre" name="genre" value="<?php if (isset($genre)) echo $genre ?>">
        <span class="error"><?php if(isset($genreErr)) echo $genreErr ?></span>
    </div>
    <div>
        <button class="button" type="submit" name="validate">Valider</button>
    </div>
</form>




<?php

require 'footer.php';
