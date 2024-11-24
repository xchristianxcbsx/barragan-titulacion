<?php 
    $host = "localhost";
    // $port = 33605;
    $username = "root";
    $password = "";
    $database = "bdusuarios2";

    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) die ("Error de conexion: ".$conn->connect_error);

    if ($_SERVER['REQUEST_METHOD']=='POST') {      
        if (isset($_POST['create'])) {
            $stmt = $conn->prepare("INSERT INTO usuarios (nombres, apellidos, email, edad, distrito) VALUES (?,?,?,?,?)");
            $stmt->bind_param("sssis", $_POST["nombres"], $_POST["apellidos"], $_POST["email"], $_POST["edad"], $_POST["distrito"]);
            $stmt->execute();
        }elseif (isset($_POST['update'])){
            $stmt = $conn->prepare("UPDATE usuarios SET nombres=?, apellidos=?, email=?, edad=?, distrito=? WHERE id=?");
            $stmt->bind_param("sssisi", $_POST["nombres"], $_POST["apellidos"], $_POST["email"], $_POST["edad"], $_POST["distrito"], $_POST["id"]);
            $stmt->execute();
            header('location: index.php');
        }elseif (isset($_POST['delete'])){
            $stmt = $conn->prepare("DELETE from usuarios WHERE id=?");
            $stmt->bind_param("i", $_POST["id"]);
            $stmt->execute();        
        }
    }   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>CRUD Miguel Ramos</title>
</head>
<body>
    <h1>Insertar y/o actualizar un Usuario</h1>
    <form action="" method="post" >
        <input type="hidden" name="id" value="<?=$_GET['edit']??''?>">
        <input type="text" name="nombres" id="nombres" placeholder="Nombres" value="<?=$_GET['nombres']??''?>">
        <input type="text" name="apellidos" id="apellidos" placeholder="Apellidos" value="<?=$_GET['apellidos']??''?>">
        <input type="email" name="email" id="email" placeholder="Email" value="<?=$_GET['email']??''?>">
        <input type="number" name="edad" id="edad" placeholder="Edad" value="<?=$_GET['edad']??''?>">
        <input type="text" name="distrito" id="distrito" placeholder="Distrito" value="<?=$_GET['distrito']??''?>">
        <button type="submit" name="<?= isset($_GET['edit'])? 'update':'create'?>">
            <?= isset($_GET['edit'])? 'Actualizar':'Crear'?>
        </button>
    </form>

    <br><br>
    <h2>Lista de Usuarios</h2>
    <table>
      <tr>
        <th>ID</th>
        <th>NOMBRES</th>
        <th>APELLIDOS</th>
        <th>EMAIL</th>
        <th>EDAD</th>
        <th>DISTRITO</th>
        <th>ACCIONES</th>
      </tr>
     
    <?php 
        $usuarios = $conn->query("SELECT * FROM usuarios");
        while ($usuario = $usuarios->fetch_assoc()):            
    ?>
        <tr>
            <td><?php echo $usuario["id"];?></td>
            <td><?php echo $usuario["nombres"];?></td>
            <td><?php echo $usuario["apellidos"];?></td>
            <td><?php echo $usuario["email"];?></td>
            <td><?php echo $usuario["edad"];?></td>
            <td><?php echo $usuario["distrito"];?></td>
            <td>
                <a href="?edit=<?=$usuario["id"]?>&nombres=<?=$usuario["nombres"]?>
                &apellidos=<?=$usuario["apellidos"]?>&email=<?=$usuario["email"]?>
                &edad=<?=$usuario["edad"]?>&distrito=<?=$usuario["distrito"]?>">Editar</a>
                <form action="" method="post">
                    <input type="hidden" name="id" value="<?=$usuario["id"]?>">
                    <button type="submit" name="delete">Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
    </table>

</body>
</html>