<?php

    Session_start();

    require "includes/connect.php";

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Get and sanitize form values
        $image_title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));

        // This will store the image path for the database
        $imagePath = null;

        //Add Code Here 
        if(isset($_FILES['image'])  && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE){

            if($_FILES['image']['error'] !== UPLOAD_ERR_OK){

                $errors[] = "There was a problem uploading your file";
            } else {

                $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];

                $detecetedType = mime_content_type($_FILES['image']['tmp_name']);

                if(!in_array($detecetedType, $allowedTypes, TRUE)){

                    $errors[] = "Only jpg, jpeg, webp, and png accepted.";
                } else {

                    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

                    $safefilename = uniqid('image_', TRUE) . '.' . strtolower($extension);

                    $destination = __DIR__ . "/uploads/" . $safefilename;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    
                        $imagePath = 'uploads/' . $safefilename;
                    } else {

                        $errors[] = "Image upload failed.";
                    }
                }
            }
        }


        // If there are no errors, insert the product into the database
        if (empty($errors)) {
            $sql = "INSERT INTO images1 (image_title, image_path)
                    VALUES (:title, :image_path)";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':title', $image_title);
            $stmt->bindParam(':image_path', $imagePath);
            $stmt->execute();

            $success = "Image added successfully!";
        }
    }

    $sql = "SELECT * from images1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $images = $stmt->fetch();
?>

<main>
    <form method="post" enctype="multipart/form-data">
        <label for="image">Upload an Image: </label>
        <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp">

        <label for="title">Image Title: </label>
        <input type="text" id="title" name="title" required>

        <button type="submit">Upload Image</button>
    </form>

    <h2>Images</h2>
    <?php if (empty($images)): ?>
        <p>No images available yet.</p>
    <?php else: ?>
        <div>
            <?php foreach ($errors as $error): ?>
                <div>
                    <?php if ($image['image_path']): ?>
                        <img src="<?= htmlspecialchars($image['image_path']); ?>" alt="<?= htmlspecialchars($image['image_title']); ?>">
                    <?php endif; ?>
                    <a href="update.php"> Update </a>
                    <a href="delete.php"> Delete </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>