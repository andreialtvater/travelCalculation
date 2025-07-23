<form method="post" id="viagemForm">
    <label>
        Data de início da viagem:
        <input type="text" name="data_inicio" id="data_inicio" required value="<?= htmlspecialchars($dataInicio ?? '') ?>">
    </label>

    <label>
        Data de retorno da viagem:
        <input type="text" name="data_fim" id="data_fim" required value="<?= htmlspecialchars($dataFim ?? '') ?>">
    </label>

    <label>
        Quantos dias são feriados?
        <input type="number" name="dias_feriados" min="0" required value="<?= htmlspecialchars($diasFeriados ?? '') ?>">
    </label>

    <label>
        A empresa oferece adicional para o café da manhã?
        <select name="paga_cafe" required>
            <option value="sim" <?= isset($empresaPagaCafe) && $empresaPagaCafe ? 'selected' : '' ?>>Sim</option>
            <option value="nao" <?= isset($empresaPagaCafe) && !$empresaPagaCafe ? 'selected' : '' ?>>Não</option>
        </select>
    </label>

    <div class="botoes">
        <button type="submit">Calcular</button>
        <a href="index.php" class="btn-limpar">Limpar</a>
    </div>
</form>

<script>
    // Função para validar se a data de fim >= data de início
    const form = document.getElementById('viagemForm');
    const dataInicioInput = document.getElementById('data_inicio');
    const dataFimInput = document.getElementById('data_fim');

    form.addEventListener('submit', function (e) {
        const dataInicio = new Date(dataInicioInput.value);
        const dataFim = new Date(dataFimInput.value);

        if (dataFim < dataInicio) {
            alert('A data de retorno não pode ser anterior à data de início da viagem.');
            e.preventDefault();
        }
    });
</script>
