function toggleFields(checkbox, row) {
    const inputs = row.querySelectorAll('input, select');
    // Habilita ou desabilita os campos com base na checkbox
    inputs.forEach(input => {
        input.disabled = !checkbox.checked;
    });
}

function saveReposicaoData() {
    const turno = document.querySelector('input[name="turno"]:checked')?.value || "";
    const tipo = document.querySelector('input[name="tipo"]:checked')?.value || "";
    
    // Captura dos dados da tabela
    const reposicoes = [];
    document.querySelectorAll('#tabela1 tbody tr').forEach((row, index) => {
        if (row.querySelector('input[type="checkbox"]').checked) {
            const dataAula = row.querySelector('input[name="data_aula_' + (index + 1) + '"]').value;
            const numeroAulas = row.querySelector('select[name="numero_aulas_' + (index + 1) + '"]').value;
            const disciplina = row.querySelector('select[name="disciplina_' + (index + 1) + '"]').value;
            const dataReposicao = row.querySelector('input[name="data_reposicao_' + (index + 1) + '"]').value;
            const horarioInicio = row.querySelector('input[name="horario_inicio_' + (index + 1) + '"]').value;
            const horarioFinal = row.querySelector('input[name="horario_final_' + (index + 1) + '"]').value;
            reposicoes.push({ dataAula, numeroAulas, disciplina, dataReposicao, horarioInicio, horarioFinal });
        }
    });

    const reposicaoData = { turno, tipo, reposicoes };
    localStorage.setItem('reposicaoData', JSON.stringify(reposicaoData));
}

function validateForm(event) {
    const table1Rows = document.querySelectorAll('#tabela1 tbody tr');
    let valid = true;
    let errorMessage = '';

    // Check Tabela 1
    table1Rows.forEach(row => {
        const checkbox = row.querySelector('input[type="checkbox"]');
        const dataInput = row.querySelector('input[name^="data_aula_"]');
        const numberSelect = row.querySelector('select[name^="numero_aulas_"]');

        if (checkbox.checked) {
            if (!dataInput.value || numberSelect.value === '') {
                valid = false;
                errorMessage += 'Por favor, preencha todos os campos obrigatórios na Tabela 1.\n';
            }
        }
    });

    // Show a single alert for all validation errors
    if (!valid) {
        alert(errorMessage);
        event.preventDefault(); // Prevent form submission if validation fails
    }
}

// Preencher a data de aula com a data de hoje
window.onload = function() {
    const today = new Date().toISOString().split('T')[0]; // Formato YYYY-MM-DD
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.value = today;
    });
};
