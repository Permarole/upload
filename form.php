<?php

// Je vérifie si le formulaire est soumis comme d'habitude

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $uploadDir = './uploads/';

    $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);


    $uploadFile = $uploadDir . uniqid($_FILES['file']['name']) . '.' . $extension;

    $fileName = $_FILES['file']['tmp_name'];

    $extensions_ok = ['jpg', 'webp', 'png'];

    $maxFileSize = 1000000;

    $data = $_POST;
    $data = array_map('trim', $data);
    $data = array_map('htmlentities', $data);

    if (empty($data["lastname"])) {
        $errors[] = 'Veuillez renseigner votre nom';
    }

    if (empty($data["firstname"])) {
        $errors[] = 'Veuillez renseigner votre prénom';
    }

    if (empty($data["age"])) {
        $errors[] = 'Veuillez renseigner votre age';
    }

    if (!empty($data["age"]) && ($data["age"] > 130 or $data["age"] < 0)) {
        $errors[] = 'Veuillez renseigner un age valide';
    }

    if ((!in_array($extension, $extensions_ok))) {

        $errors[] = 'Veuillez sélectionner une image de type Jpg ou Webp ou Png !';
    }

    /****** On vérifie si l'image existe et si le poids est autorisé en octets *************/

    if (file_exists($_FILES['file']['tmp_name']) && filesize($_FILES['file']['tmp_name']) > $maxFileSize) {

        $errors[] = "Votre fichier doit faire moins de 1Mo !";
    }




    /****** Si je n'ai pas d"erreur alors j'upload *************/

    move_uploaded_file($fileName, $uploadFile);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Upload</title>
    <style>
        .submit {
            margin-bottom: 4em;
        }
    </style>
</head>

<body class="container-fluid">
    <h1>Upload your file</h1>
    <?php if (isset($errors)) { ?>
        <ul>
            <?php foreach ($errors as $error) { ?>
                <li><?= $error ?></li>
            <?php } ?>
        </ul>
    <?php } ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="row justify-content-center">
            <label for="lastname" class="col-8">Lastname</label>
            <input type="text" name="lastname" id="lastname" class="col-8" value="<?php echo isset($_POST['lastname']) ? $_POST['lastname'] : '' ?>">
        </div>
        <div class="row justify-content-center">
            <label for="firstname" class="col-8">Firstname</label>
            <input type="text" name="firstname" id="firstname" class="col-8" value="<?php echo isset($_POST['firstname']) ? $_POST['firstname'] : '' ?>">
        </div>
        <div class="row justify-content-center">
            <label for="age" class="col-8">Age</label>
            <input type="number" name="age" id="age" class="col-8" value="<?php echo isset($_POST['age']) ? $_POST['age'] : '' ?>">
        </div>
        <div class="row justify-content-center">
            <label for="file" class="col-8">Photo</label>
            <input type="file" name="file" id="file" accept=".jpg,.png,.webp" class="col-8">
        </div>
        <div class="row justify-content-center submit">
            <input type="submit" value="Submit" class="col-2">
        </div>
    </form>

    <?php if (($_SERVER['REQUEST_METHOD'] === "POST") && empty($errors)) { ?>
        <div class="container">
            <div class="row border border-primary rounded col-8">
                <img src="<?= $uploadFile ?>" alt="profil pic" class="col-6">
                <div class="col-6 align-self-center">
                    <div class="row">
                        <p class="col-6"><?= $data['firstname'] . ' ' . $data['lastname'] ?></p>
                    </div>
                    <div class="row">
                        <p class="col-6"><?= $data['age'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

</body>

</html>