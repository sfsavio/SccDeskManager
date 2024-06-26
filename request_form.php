<?php
session_start(); // Start a new session or resume an existing session
date_default_timezone_set('America/Sao_Paulo'); // Set the right timezone
// Check if the user is logged in
if (!isset($_SESSION['username'])) { // Checa se "username" é <>
    header('Location: index.php'); // Redirect to index.php if not logged in
    exit;
}

// Function to load requests from the JSON file
function loadRequests()
{
    $filename = 'requests.json';
    if (!file_exists($filename)) {
        return []; // Return an empty array if the file does not exist
    }
    $data = file_get_contents($filename); // Read the file contents
    return json_decode($data, true); // Decode the JSON data into a PHP array
}

// Function to save requests to the JSON file
function saveRequests($data)
{
    $filename = 'requests.json';
    file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT)); // Encode and save the data
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $assunto = $_POST['assunto'];
    $setor = $_POST['setor'];
    $descricao = $_POST['descricao'];
    $datetime = date('Y-m-d H:i:s'); // Current date and time

    $requests = loadRequests(); // Load existing requests

    // Add the new request to the array
    $requests[] = [
        'assunto' => $assunto,
        'setor' => $setor,
        'descricao' => $descricao,
        'datetime' => $datetime,
        'username' => $_SESSION['username'],
        'status' => 'pendente'
    ];

    saveRequests($requests); // Save the updated requests array

    header('Location: request_form.php'); // Button Submit has influence here!! <========
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a Request</title>
    <link rel="stylesheet" type="text/css" href="./styles/request_form.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
        rel="stylesheet">
</head>

<body>
    <header>
        <nav>
            <p><span><?php echo htmlspecialchars($_SESSION['nome']); ?>!</span></p>
            <!-- Display the user's name -->
            <!-- Para todos os usuários -->
            <p class="home_shortcut"><a href="home.php">Início</a></p>

            <p class="create"><a href="request_form.php">Criar chamado</a></p>

            <!-- Checa se o usuário tem as permissões de administrador -->
            <?php if ($_SESSION['is_admin']): ?>
                <!-- Somente para admins -->
                <p><a href="view_backlog.php">Login Backlog</a></p>

                <p><a href="manage_requests.php">Gerenciar Chamados</a></p>
            <?php endif; ?>

            <p><a href="logout.php">Sair</a></p> <!-- Logout link -->
        </nav>
    </header>
    <main>
        <section>
            <!-- <h2>Abertura de Chamado!</h2> -->
            <form action="request_form.php" method="post">
                <label for="assunto">Assunto:</label>
                <select id="assunto" name="assunto" required>
                    <option value="convenio">Convênio</option>
                    <option value="impressora">Impressora</option>
                    <option value="internet">Internet</option>
                    <option value="leitor de biometria">Leitor de Biometria</option>
                    <option value="leitor de cartao">Leitor de Cartão</option>
                    <option value="monitor">Monitor</option>
                    <option value="mouse">Mouse</option>
                    <option value="raio x">Raio X</option>
                    <option value="teclado">Teclado</option>
                    <option value="televisao">Televisão</option>
                    <option value="ultrassom">Ultrassom</option>
                </select>
                <label for="setor">Setor:</label>
                <select id="setor" name="setor" required>
                    <option value="assesoria">Assesoria</option>
                    <option value="agendamento">Agendamento</option>
                    <option value="almoxarifado">Almoxarifado</option>
                    <option value="clinica médica">Clínica Médica</option>
                    <option value="CME">CME</option>
                    <option value="compras">Compras</option>
                    <option value="conforto médico">Conforto Médico</option>
                    <option value="Departamento pessoal">Departamento Pessoal</option>
                    <option value="farmácia">Farmácia</option>
                    <option value="faturamento">Faturamento</option>
                    <option value="financeiro">Financeiro</option>
                    <option value="manutenção">Manutenção</option>
                    <option value="maternidade">Maternidade</option>
                    <option value="posto de enfermagem clínica">Posto de Enfermagem Clínica</option>
                    <option value="posto de enfermagem pronto atendimento">Posto de Enfermagem Pronto Atendimento
                    </option>
                    <option value="recepção principal">Recepção Principal</option>
                    <option value="raio x">Raio X</option>
                    <option value="recepção pronto atendimento">Recepção Pronto Atendimento</option>
                    <option value="SAME">SAME</option>
                    <option value="SND">SND</option>
                    <option value="tesouraria">Tesouraria</option>
                    <option value="triagem">Triagem</option>
                </select>
                <label for="descricao">Descrição:</label><br><br>
                <textarea id="descricao" name="descricao" rows="3" cols="100"></textarea><br>
                <button type="submit" onclick="alert('Sucesso!')" id="btt">Enviar</button>
            </form>
        </section>
    </main>
</body>

</html>