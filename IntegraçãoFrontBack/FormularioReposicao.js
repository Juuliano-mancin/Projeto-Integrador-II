function toggleFields(checkbox, row) {
    const inputs = row.querySelectorAll('input, select');
    // Habilita ou desabilita os campos com base na checkbox
    inputs.forEach(input => {
        input.disabled = !checkbox.checked;
    });
}

function validateForm(event) {
    const table1Rows = document.querySelectorAll('#tabela1 tbody tr');
    const table2Rows = document.querySelectorAll('#tabela2 tbody tr');
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

    // Check Tabela 2
    table2Rows.forEach(row => {
        const checkbox = row.querySelector('input[type="checkbox"]');
        const dataInput = row.querySelector('input[name^="data_reposicao_"]');
        const startInput = row.querySelector('input[name^="horario_inicio_"]');
        const endInput = row.querySelector('input[name^="horario_final_"]');

        if (checkbox.checked) {
            if (!dataInput.value || !startInput.value || !endInput.value) {
                valid = false;
                errorMessage += 'Por favor, preencha todos os campos obrigatórios na Tabela 2.\n';
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

