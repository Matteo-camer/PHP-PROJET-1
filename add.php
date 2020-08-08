<?php
    include('config/db_connect.php');

    $email = $title = $ingredients = '';
    $errors = array('email' => '', 'title' => '', 'ingredients' => '');

    if(isset($_POST['submit'])){

        //check email
        if(empty($_POST['email'])){
            $errors['email'] = "An email is required <br />";
        }else{
            $email = $_POST['email'];
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $errors['email'] = "Email must be a valid address email";
            }
        }

        //check title
        if(empty($_POST['title'])){
            $errors['title'] = "A title is required <br />";
        }else{
            $title = $_POST['title'];
            if(!preg_match('/^[a-zA-Z\s]+$/', $title)){
                $errors['title'] = "Title must be letters and spaces only <br />";
            }
        }

        //check ingredients
        if(empty($_POST['ingredients'])){
            $errors['ingredients'] = "At least one ingredient is required <br />";
        }else{
            $ingredients = $_POST['ingredients'];
            if(!preg_match('/^([a-zA-Z\s]+)(,\s*[a-zA-Z\s]*)*$/', $ingredients)){
                $errors['ingredients'] = "Ingredients must be a coma separated list";
            }
        }
    }

    if(array_filter($errors)){
        //echo "Error in the form";
    }else{
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);

        //sql request
        $sql = "INSERT INTO pizzas(title, email, ingredients) VALUES('$title', '$email', '$ingredients')";

        //save to the db and check
        if(mysqli_query($conn, $sql)){
            //success
            header('Location: index.php');
        }else{
            //error
            echo "query error: " . mysqli_error($conn);
        }

    }

?>
<!DOCTYPE html>
<html lang="en">
    <?php include('templates/header.php')?>

    <section class="container grey-text">
        <h4 class="center">Add a pizza</h4>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="white">
            <label>Your Email:</label>
            <input type="text" name="email" value= "<?php echo htmlspecialchars($email) ?>">
            <div class="red-text"><?php echo $errors['email']; ?></div>

            <label>Pizza title:</label>
            <input type="text" name="title" value= "<?php echo htmlspecialchars($title) ?>">
            <div class="red-text"><?php echo $errors['title']; ?>

            <label>Ingredients(coma separated):</label>
            <input type="text" name="ingredients" value= "<?php echo htmlspecialchars($ingredients) ?>">
            <div class="red-text"><?php echo $errors['ingredients']; ?>

            <div class="center">
                <input type="submit" name="submit" value="submit" class="btn brand z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php')?>
</html>
