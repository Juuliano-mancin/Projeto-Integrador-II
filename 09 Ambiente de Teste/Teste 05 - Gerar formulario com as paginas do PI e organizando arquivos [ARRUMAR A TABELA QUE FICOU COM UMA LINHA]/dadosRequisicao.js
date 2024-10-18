// Carregar os dados do LocalStorage e exibir na página
function loadData() {
    const requisicaoData = JSON.parse(localStorage.getItem('requisicaoData'));
    const reposicaoData = JSON.parse(localStorage.getItem('reposicaoData'));

    if (requisicaoData) {
        document.getElementById('curso').innerText = requisicaoData.curso;
        document.getElementById('data-inicial').innerText = requisicaoData.dataInicial;
        document.getElementById('data-final').innerText = requisicaoData.dataFinal;
        document.getElementById('justificativa').innerText = requisicaoData.justificativa;
        document.getElementById('comentarios').innerText = requisicaoData.comentarios;
    }

    if (reposicaoData) {
        document.getElementById('turno').innerText = reposicaoData.turno;
        document.getElementById('tipo').innerText = reposicaoData.tipo;

        const reposicoesDiv = document.getElementById('reposicoes');
        reposicaoData.reposicoes.forEach((reposicao, index) => {
            reposicoesDiv.innerHTML += `
                <div>
                    <h5>Reposição ${index + 1}</h5>
                    <p><strong>Data da Aula:</strong> ${reposicao.dataAula}</p>
                    <p><strong>Número de Aulas:</strong> ${reposicao.numeroAulas}</p>
                    <p><strong>Disciplina:</strong> ${reposicao.disciplina}</p>
                    <p><strong>Data da Reposição:</strong> ${reposicao.dataReposicao}</p>
                    <p><strong>Horário Início:</strong> ${reposicao.horarioInicio}</p>
                    <p><strong>Horário Final:</strong> ${reposicao.horarioFinal}</p>
                </div>
            `;
        });
    }
}

// Função para gerar o PDF
function gerarPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Adiciona o cabeçalho
    doc.setFontSize(16);
    doc.text("FACULDADE DE TECNOLOGIA (FATEC)", 10, 10);
    doc.setFontSize(12);
    doc.text("PORTAL JUSTIFICATIVA DE FALTAS", 10, 15);
    doc.text("Documento Oficial", 10, 20);
    doc.setLineWidth(0.5);
    doc.line(10, 25, 200, 25); // Linha horizontal

    // Título do documento
    doc.setFontSize(14);
    doc.text("Dados da Requisição e Reposição de Aulas", 10, 35);

    // Seção 1: Dados da Requisição de Faltas
    const requisicaoData = JSON.parse(localStorage.getItem('requisicaoData'));
    if (requisicaoData) {
        doc.setFontSize(12);
        doc.text("Dados da Justificativa de Faltas", 10, 45);
        doc.text(`Curso: ${requisicaoData.curso}`, 10, 55);
        doc.text(`Data Inicial: ${requisicaoData.dataInicial}`, 10, 60);
        doc.text(`Data Final: ${requisicaoData.dataFinal}`, 10, 65);
        doc.text(`Justificativa: ${requisicaoData.justificativa}`, 10, 70);
        doc.text(`Comentários: ${requisicaoData.comentarios}`, 10, 75);
    }

    // Seção 2: Dados da Reposição de Aulas
    const reposicaoData = JSON.parse(localStorage.getItem('reposicaoData'));
    if (reposicaoData) {
        doc.setFontSize(12);
        doc.text("Dados da Reposição de Aulas", 10, 85);
        doc.text(`Turno: ${reposicaoData.turno}`, 10, 95);
        doc.text(`Tipo de Reposição: ${reposicaoData.tipo}`, 10, 100);

        // Listar todas as reposições
        reposicaoData.reposicoes.forEach((reposicao, index) => {
            const yOffset = 110 + index * 30; // Espaçamento entre reposições
            doc.text(`Reposição ${index + 1}:`, 10, yOffset);
            doc.text(`Data da Aula: ${reposicao.dataAula}`, 10, yOffset + 10);
            doc.text(`Número de Aulas: ${reposicao.numeroAulas}`, 10, yOffset + 20);
            doc.text(`Disciplina: ${reposicao.disciplina}`, 80, yOffset + 10); // Colocar a disciplina mais à direita
            doc.text(`Data da Reposição: ${reposicao.dataReposicao}`, 10, yOffset + 30);
            doc.text(`Horário Início: ${reposicao.horarioInicio}`, 80, yOffset + 20);
            doc.text(`Horário Final: ${reposicao.horarioFinal}`, 80, yOffset + 30);
        });
    }

    // Adiciona um rodapé
    doc.setFontSize(10);
    doc.setTextColor(150);
    doc.text("Este documento é gerado eletronicamente e não requer assinatura.", 10, 280);
    doc.setTextColor(0); // Reseta a cor do texto

    // Baixar o PDF
    doc.save("dados_requisicao_reposicao.pdf");
}


// Carregar os dados ao iniciar a página
window.onload = loadData;
