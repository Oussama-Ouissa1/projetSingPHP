<?php 


function register($fullName , $email , $password , $confirmPassword){
    
        // Vérification des champs
        if (empty($fullName) || empty($email) || empty($password) || empty($confirmPassword)) {
            $message = "<p class='text-red-500'>Tous les champs sont obligatoires.</p>";
        } elseif ($password !== $confirmPassword) {
            $message = "<p class='text-red-500'>Les mots de passe ne correspondent pas.</p>";
        } else {

            include "conn.php";

            // Vérifier si l'email existe déjà
            $check = $conn->prepare("SELECT id FROM users WHERE email = :email");
            $check->bindParam(':email', $email);
            $check->execute();

            if ($check->rowCount() > 0) {
                $message = "<p class='text-red-500'>Cet email est déjà utilisé.</p>";
            } else {
                // Hachage du mot de passe
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insertion
                $sql = "INSERT INTO users (fullName, email, password) VALUES (:fullName, :email, :password)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':fullName', $fullName);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->execute();

                $message = "<p class='text-green-500'>Compte créé avec succès !</p>";
            }
        }
    }
?>