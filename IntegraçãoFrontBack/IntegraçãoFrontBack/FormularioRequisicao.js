// Seleciona todos os inputs do tipo radio e os selects correspondentes
const radios = document.querySelectorAll('input[name="justificativa"]');
const selects = {
    'licenca-falta-radio': document.getElementById('justificativa1'),
    'faltas-injustificadas-radio': document.getElementById('justificativa2'),
    'faltas-justificadas-radio': document.getElementById('justificativa3'),
    'faltas-previstas-radio': document.getElementById('justificativa4')
};

// Função de depuração para ver se o JavaScript está sendo carregado corretamente
console.log("JavaScript carregado corretamente");

// Preenche os campos de data com a data atual
const today = new Date().toISOString().split('T')[0]; // Formato YYYY-MM-DD
document.getElementById('data-inicial').value = today;
document.getElementById('data-final').value = today;

// Adiciona um event listener para verificar as datas
document.getElementById('data-final').addEventListener('change', () => {
    const dataInicial = new Date(document.getElementById('data-inicial').value);
    const dataFinal = new Date(document.getElementById('data-final').value);
    
    if (dataFinal < dataInicial) {
        alert('A data final não pode ser anterior à data inicial.');
        document.getElementById('data-final').value = today; // Resetando para a data atual
    }
});

// Adiciona um event listener para cada input do tipo radio
radios.forEach(radio => {
    radio.addEventListener('change', () => {
        // Depuração para garantir que o evento de mudança do radio está funcionando
        console.log(`Rádio selecionado: ${radio.id}`);

        // Desabilita todos os selects primeiro
        Object.values(selects).forEach(select => {
            select.disabled = true;  // Desabilita todos os selects
            select.value = '';  // Reseta a seleção de todos os selects
        });

        // Ativa apenas o select correspondente ao rádio selecionado
        if (radio.checked) {
            console.log(`Habilitando o select correspondente: ${radio.id}`);
            selects[radio.id].disabled = false;
        }
    });
});

// Função para abrir o seletor de arquivo ao clicar no botão de upload
const uploadButton = document.getElementById('upload-documento');
const inputFile = document.getElementById('input-file');

uploadButton.addEventListener('click', () => {
    inputFile.click(); // Simula um clique no input do tipo file
});

// Função para capturar o arquivo selecionado
inputFile.addEventListener('change', (event) => {
    const selectedFile = event.target.files[0];
    if (selectedFile) {
        alert(`Ficheiro selecionado: ${selectedFile.name}`);
    }
});
