
        <?php

        session_start();

        require("conexão.php");
        
        $email = $_POST["email"]; 
        $senha = $_POST["senha"];
        

        $sql = "SELECT * FROM usuarios 
        WHERE email = '$email' 
        AND senha = '$senha'";

        $resultado = mysqli_query($conn, $sql);

        if (mysqli_num_rows($resultado) > 0) {

        $usuario = mysqli_fetch_assoc($resultado);

        $_SESSION["id"] = $usuario["id"];
        $_SESSION["nome"] = $usuario["nome"];
        $_SESSION["email"] = $usuario["email"];
        $_SESSION["tipo"] = $usuario["tipo"];

        header("Location: index.php");
        exit;

        } else {

        echo "E-mail ou senha incorretos.";

        }




        ?>
