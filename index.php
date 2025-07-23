<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/classes/CalculoViagem.php';
session_start();

$dataInicio = $dataFim = null;
$totalDias = $diasFeriados = $diasFimSemana = null;
$empresaPagaCafe = false;
$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dataInicio = $_POST['data_inicio'] ?? '';
    $dataFim = $_POST['data_fim'] ?? '';
    $diasFeriados = (int) ($_POST['dias_feriados'] ?? 0);
    $empresaPagaCafe = ($_POST['paga_cafe'] ?? '') === 'sim';

    if ($dataInicio && $dataFim) {
        $d1 = new DateTime($dataInicio);
        $d2 = new DateTime($dataFim);
        $interval = $d1->diff($d2);
        $totalDias = $interval->days + 1;

        $diasFimSemana = 0;
        $period = new DatePeriod($d1, new DateInterval('P1D'), (clone $d2)->modify('+1 day'));
        foreach ($period as $dt) {
            if (in_array($dt->format('N'), [6,7])) {
                $diasFimSemana++;
            }
        }
    }

    $calculo = new CalculoViagem($totalDias, $diasFimSemana, $diasFeriados, $empresaPagaCafe);
    $calculo->calcular();

    $_SESSION['resultado'] = $calculo->toArray();
    $_SESSION['dataInicio'] = $dataInicio;
    $_SESSION['dataFim'] = $dataFim;
    $_SESSION['totalDias'] = $totalDias;
    $_SESSION['diasFeriados'] = $diasFeriados;
    $_SESSION['diasFimSemana'] = $diasFimSemana;
    $_SESSION['empresaPagaCafe'] = $empresaPagaCafe;

    header('Location: index.php');
    exit;
}

if (isset($_SESSION['resultado'])) {
    $resultado = $_SESSION['resultado'];
    $dataInicio = $_SESSION['dataInicio'];
    $dataFim = $_SESSION['dataFim'];
    $totalDias = $_SESSION['totalDias'];
    $diasFeriados = $_SESSION['diasFeriados'];
    $diasFimSemana = $_SESSION['diasFimSemana'];
    $empresaPagaCafe = $_SESSION['empresaPagaCafe'];

    unset($_SESSION['resultado'], $_SESSION['dataInicio'], $_SESSION['dataFim'],
          $_SESSION['totalDias'], $_SESSION['diasFeriados'], $_SESSION['diasFimSemana'],
          $_SESSION['empresaPagaCafe']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Calcular valor da viagem</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>
    <button id="btnToggleDark" class="sun" title="Alternar modo escuro"></button>

    <div class="container">
        <h1>Calcular valor da viagem</h1>
        <?php include 'templates/form.php'; ?>
        <?php if ($resultado): ?>
            <?php include 'templates/resultado.php'; ?>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("input[name='data_inicio']", { dateFormat: "Y-m-d", locale: "pt" });
        flatpickr("input[name='data_fim']", { dateFormat: "Y-m-d", locale: "pt" });

        const btn = document.getElementById('btnToggleDark');
        const stored = localStorage.getItem('darkMode') === 'true';
        document.body.classList.toggle('dark-mode', stored);
        btn.classList.toggle('moon', stored);
        btn.classList.toggle('sun', !stored);
        btn.onclick = () => {
            const isDark = document.body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', isDark);
            btn.classList.toggle('moon', isDark);
            btn.classList.toggle('sun', !isDark);
        };
    </script>
</body>
</html>
