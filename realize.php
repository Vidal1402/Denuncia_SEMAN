<?php
$conexao = new mysqli("localhost", "root", "cefet123", "Denuncias_BD");

if ($conexao->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conexao->connect_error);
}

$sql = "SELECT id, categoria, descricao, status FROM denuncias";
$resultado = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denúncias Realizadas</title>
    <link rel="icon" href="img/seman.png" type="image/png">
    <link rel="stylesheet" href="style_Realize.css?v=1">
 
</head>
<body>

<div class="top-bar">
    <div class="bgldoido">
        <a class="sobrenos" href="sobrenos.php">
            Sobre nós  
        </a>
       
        <a class="sobreno" href="contato.php">
            Contatos
        </a>
        <a class="bar2">
            |
        </a>
        <a class="sobren" href="estatistica.php">
            Estatísticas
        </a>
        <a class="bar3">
            |
        </a>
    </div> 
            
    <span><img class="seman" src="img/sema.png" alt="Logo SEMAN"></span>
    <span><img class="prefeitura" src="img/PREFEITURANI.png" alt="Logo PREFEITURA"></span>
</div>

<div class="order">
    <div class="container">
        <h2>Denúncias Realizadas</h2>
        <?php
        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                // Atribuir a classe baseada no status da denúncia
                $statusClasse = $row['status'] === 'atendida' ? 'atendida' : 'nao-atendida';
    
                echo "<div class='denuncia $statusClasse'>";
                echo "<h3>ID da Denúncia: " . $row['id'] . "</h3>";
                echo "<p><strong>Categoria:</strong> " . $row['categoria'] . "</p>";
                echo "<p><strong>Descrição:</strong> " . $row['descricao'] . "</p>";
                echo "<p><strong>Status:</strong> " . ucfirst($row['status']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>Nenhuma denúncia encontrada.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>


