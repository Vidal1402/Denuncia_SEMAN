<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estatísticas</title>
    <link rel="stylesheet" href="style.css?v=1">
    <link rel="icon" href="img/seman.png" type="image/png">
    <!-- Adicione a biblioteca Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
    
    .top-bar {
        width: 100%;
        background-color: #433f87;
        color: white;
        padding: 1%;
        text-align: center;
        border-bottom: 6.5px solid #e75925; 
        position: fixed;
        z-index: 1000;
    }
  
   
    canvas {
        width: 100%; /* Ajuste a largura conforme necessário */
        margin: 1px auto; /* Centraliza o gráfico na tela */
        padding: 3%;
    }
    .titulo {
        text-align: center;
        margin-top: -100px; /* Ajuste o valor conforme necessário para mover todo o conteúdo para mais acima */
    }
    .container{
        margin-top:15%;
    }
   
</style>
<body>

<div class="top-bar">
        <div class="bgldoido">
        <a href="denuncia.php">
        <div class="esta"><u>Voltar</u></div>
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
            <span><img class="seman" src="img/sema.png" alt="Logo SEMAN"></span>
            <span><img class="prefeitura" src="img/PREFEITURANI.png" alt="Logo PREFEITURA"></span>
        </div> 
    </div>
  
<div class="container">
    <canvas id="graficoCategoria"></canvas>
</div>

<script>
<?php
$conexao = new mysqli("localhost", "root", "cefet123", "Denuncias_BD");

if ($conexao->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conexao->connect_error);
}

$query = "SELECT categoria, COUNT(*) as quantidade FROM denuncias GROUP BY categoria";
$resultado = $conexao->query($query);

$categorias = [];
$quantidades = [];

while ($row = $resultado->fetch_assoc()) {
    $categorias[] = $row['categoria'];
    $quantidades[] = $row['quantidade'];
}

$conexao->close();
?>

var config = {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($categorias); ?>,
        datasets: [{
            label: 'Quantidade de Denúncias por Categoria',
            data: <?php echo json_encode($quantidades); ?>,
            backgroundColor: '#e75925',
            borderColor: '#fc4401', 
            borderWidth: 4
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
};
var config = {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($categorias); ?>,
        datasets: [{
            label: 'Quantidade de Denúncias por Categoria',
            data: <?php echo json_encode($quantidades); ?>,
            backgroundColor: '#e75925',
            borderColor: '#fc4401',
            borderWidth: 4
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#ffffff' // Cor branca para os números do eixo Y
                }
            },
            x: {
                ticks: {
                    color: '#ffffff' // Cor branca para os números do eixo X
                }
            }
        },
        plugins: {
            legend: {
                labels: {
                    color: '#ffffff' // Cor branca para o texto da legenda
                }
            }
        }
    }
};

var ctx = document.getElementById('graficoCategoria').getContext('2d');
var myChart = new Chart(ctx, config);
</script>
</body>
</html>