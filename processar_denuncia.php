<?php
$conexao = new mysqli("localhost", "root", "cefet123", "Denuncias_BD");

if ($conexao->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conexao->connect_error);
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoria = $conexao->real_escape_string($_POST['categoria']);
    $descricao = $conexao->real_escape_string($_POST['descricao']);
    $dataAtual = date('Y-m-d H:i:s'); // Inclui hora para o registro detalhado
    $imagemNome = null;

    $baseUploadDir = 'uploads';
    
    // Criar um novo diretório numerado com base na data atual e no timestamp atual
    $timestamp = time(); // Obtém o timestamp atual
    $dataDir = date('Y-m-d'); // Cria uma pasta com a data atual
    $uploadDir = $baseUploadDir . '/' . $dataDir . '/' . $timestamp;

    // Verificar se o diretório existe, se não, criar
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            $mensagem = 'Falha ao criar o diretório "' . $uploadDir . '".';
            echo $mensagem . '<br>';
        }
    }

    // Verificar e processar o upload de imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $imagemNome = uniqid() . '-' . basename($_FILES['imagem']['name']);
        $uploadFile = $uploadDir . '/' . $imagemNome;
        
        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowedTypes)) {
            $mensagem = 'Somente arquivos JPG, JPEG, PNG e GIF são permitidos.';
            echo $mensagem . '<br>';
        } else {
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $uploadFile)) {
                $mensagem = 'Arquivo enviado com sucesso para ' . $uploadFile;
                echo $mensagem . '<br>';
            } else {
                $mensagem = 'Falha ao mover o arquivo para ' . $uploadFile;
                echo $mensagem . '<br>';
            }
        }
    } else {
        $mensagem = isset($_FILES['imagem']) ? 'Erro no envio. Código: ' . $_FILES['imagem']['error'] : 'Nenhum arquivo enviado.';
        echo $mensagem . '<br>';
    }

    // Inserir a denúncia no banco de dados
    $sql = "INSERT INTO denuncias (categoria, descricao, data) VALUES ('$categoria', '$descricao', '$dataAtual')";

    if ($conexao->query($sql) === TRUE) {
        $mensagem = "Denúncia enviada com sucesso!";
        
        // Criar o relatório da denúncia
        $relatorio = "Relatório da Denúncia\n";
        $relatorio .= "Data: $dataAtual\n";
        $relatorio .= "Categoria: $categoria\n";
        $relatorio .= "Descrição: $descricao\n";
        $relatorio .= $imagemNome ? "Imagem: $uploadFile\n" : "Imagem: Não enviada\n";

        // Nome do arquivo de relatório
        $relatorioFile = $uploadDir . '/relatorio_' . $timestamp . '.txt';

        // Gravar o relatório no arquivo
        file_put_contents($relatorioFile, $relatorio);

        // Enviar notificação com o Notifu
        $tituloNotificacao = "Nova Denúncia Recebida";
        $mensagemNotificacao = "Categoria: $categoria\nDescrição: $descricao";
        $comando = "C:\\notifu-1.7.1\\Notifu.exe /m \"$mensagemNotificacao\" /p \"$tituloNotificacao\"";
        exec($comando);

    } else {
        $mensagem = "Erro ao enviar a denúncia: " . $conexao->error;
    }
}

$conexao->close();

header("Location: denuncia.php?mensagem=" . urlencode($mensagem));
exit();
?>
