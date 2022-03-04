<?php

$titre = 'Modifier un livre';

require 'header.php';

$bookId = $_GET['id'];

$datas = $pdo->prepare("SELECT livre.*, auteur.nom, auteur.prenom, genre.genre_nom FROM livre JOIN auteur ON livre.auteur_id = auteur.id JOIN genre ON livre.genre_id = genre.id WHERE livre.id =:bookId");

$datas->bindValue(":bookId", $bookId, PDO::PARAM_INT);

$datas->execute();

$data = $datas->fetchAll(PDO::FETCH_OBJ);

foreach ($data as $bookData) :
    $bookTitre = $bookData->titre;
    $bookPrenomAuteur = $bookData->prenom;
    $bookNomAuteur = $bookData->nom;
    $bookPublication = $bookData->publication;
    $bookEdition = $bookData->edition;
    $bookResume = $bookData->resume;
    $bookPrix = $bookData->prix;
    $bookGenre = $bookData->genre_nom;
endforeach;

if (isset($_POST['validate'])) {
    $authorFirstname = $_POST['authorFirstname'];
    $authorLastname = $_POST['authorLastname'];
    $title = $_POST['title'];
    $parution = $_POST['parution'];
    $edition = $_POST['edition'];
    $resume = $_POST['resume'];
    $prix = $_POST['price'];
    $genre = $_POST['genre'];

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


    $queryUpdate = $pdo->prepare("UPDATE livre SET titre =:titre, publication =:parution, edition =:edition, resume =:resume, prix =:prix, auteur_id = '$auteur_id', genre_id = '$genre_id' WHERE id =:bookId");

    $queryUpdate->bindValue(":titre", $title, PDO::PARAM_STR);
    $queryUpdate->bindValue(":parution", $parution, PDO::PARAM_STR);
    $queryUpdate->bindValue(":edition", $edition, PDO::PARAM_STR);
    $queryUpdate->bindValue(":resume", $resume, PDO::PARAM_STR);
    $queryUpdate->bindValue(":prix", $prix, PDO::PARAM_STR);
    $queryUpdate->bindValue(":bookId", $bookId, PDO::PARAM_INT);

    $queryUpdate->execute();

    header('location:index.php');
}

?>
<p>|</p>
<h1 class="m-top">Modifier un livre</h1>
<form class="form" action="" method="post">
    <div>
        <label for="title">Titre :</label>
        <input type="text" id="title" name="title" value="<?php echo $bookTitre ?>">
    </div>
    <div>
        <label for="authorFirstname">Auteur :</label>
        <input type="text" id="authorFirstname" name="authorFirstname" value="<?php echo $bookPrenomAuteur ?>">
    </div>
    <div>
        <label for="authorLastname"></label>
        <input type="text" id="authorLastname" name="authorLastname" value="<?php echo $bookNomAuteur ?>">
    </div>
    <div>
        <label for="parution">Date de parution :</label>
        <input type="date" id="parution" name="parution" value="<?php echo $bookPublication ?>">
    </div>
    <div>
        <label for="edition">Editeur :</label>
        <input type="text" id="edition" name="edition" value="<?php echo $bookEdition ?>">
    </div>
    <div>
        <label for="resume">Résumé :</label>
        <textarea id="resume" name="resume"><?php echo $bookResume ?></textarea>
    </div>
    <div>
        <label for="price">Prix : (en €)</label>
        <input type="text" id="price" name="price" value="<?php echo $bookPrix ?>">
    </div>
    <div>
        <label for="genre">Genre :</label>
        <input type="text" id="genre" name="genre" value="<?php echo $bookGenre ?>">
    </div>
    <div>
        <button class="button" type="submit" name="validate">Modifier</button>
    </div>
</form>

<?php

require 'footer.php';
