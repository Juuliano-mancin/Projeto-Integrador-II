-- Criação do banco de dados
CREATE DATABASE justificativafaltas;
USE justificativafaltas;

-- Criação da tabela tb_professores
CREATE TABLE tb_professores (
    idprofessor INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    sobrenome VARCHAR(255) NOT NULL,
    matricula INT UNIQUE NOT NULL,
    emailinstitucional VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    Funcao ENUM('Professor', 'Administrativo', 'Coordenacao') NOT NULL
);

-- Inserção de dados na tabela tb_professores
INSERT INTO tb_professores (nome, sobrenome, matricula, emailinstitucional, senha, Funcao)
VALUES 
('João', 'Silva', 1001, 'joao.silva@fatec.sp.gov.br', 'fatecitapira', 'Administrativo'),
('Maria', 'Souza', 1002, 'maria.souza@fatec.sp.gov.br', 'fatecitapira', 'Administrativo'),
('Carlos', 'Pereira', 1003, 'carlos.pereira@fatec.sp.gov.br', 'fatecitapira', 'Administrativo'),
('Ana', 'Oliveira', 1004, 'ana.oliveira@fatec.sp.gov.br', 'fatecitapira', 'Coordenacao'),
('Fernanda', 'Lima', 1005, 'fernanda.lima@fatec.sp.gov.br', 'fatecitapira', 'Coordenacao'),
('Bruno', 'Santos', 1006, 'bruno.santos@fatec.sp.gov.br', 'fatecitapira', 'Professor'),
('Tatiane', 'Almeida', 1007, 'tatiane.almeida@fatec.sp.gov.br', 'fatecitapira', 'Professor'),
('Ricardo', 'Cunha', 1008, 'ricardo.cunha@fatec.sp.gov.br', 'fatecitapira', 'Professor'),
('Juliana', 'Ribeiro', 1009, 'juliana.ribeiro@fatec.sp.gov.br', 'fatecitapira', 'Professor'),
('Renato', 'Macedo', 1010, 'renato.macedo@fatec.sp.gov.br', 'fatecitapira', 'Professor');

-- Criação da tabela tb_cursos
CREATE TABLE tb_cursos (
    idcurso INT AUTO_INCREMENT PRIMARY KEY,
    curso VARCHAR(255) NOT NULL,
    siglacurso VARCHAR(10) NOT NULL UNIQUE
);

-- Inserção de dados na tabela tb_cursos
INSERT INTO tb_cursos (curso, siglacurso)
VALUES 
('Desenvolvimento Software Multiplataforma', 'DSM'),
('Gestão da Produção Industrial', 'GPI'),
('Gestão Industrial', 'GE');

-- Criação da tabela tb_disciplinas
CREATE TABLE tb_disciplinas (
    iddisciplina INT AUTO_INCREMENT PRIMARY KEY,
    disciplina VARCHAR(255) NOT NULL,
    siglacurso VARCHAR(10),
    FOREIGN KEY (siglacurso) REFERENCES tb_cursos(siglacurso)
);

-- Inserção de dados na tabela tb_disciplinas
INSERT INTO tb_disciplinas (disciplina, siglacurso)
VALUES 
('Sistemas Operacionais', 'DSM'),
('Redes de Computadores', 'DSM'),
('Design Digital', 'DSM'),
('Algoritmo e Lógica de Programação', 'DSM'),
('Desenvolvimento Web I', 'DSM'),
('Modelagem de Banco de Dados', 'DSM'),
('Engenharia de Softwares I', 'DSM'),
('Banco de Dados Relacional', 'DSM'),
('Engenharia de Softwares II', 'DSM'),
('Matemática para Computação', 'DSM'),
('Desenvolvimento Web II', 'DSM'),
('Técnicas de Programação', 'DSM'),
('Estrutura de Dados', 'DSM'),
('Fundamentos de Administração', 'GE'),
('Logística e Cadeia de Suprimentos', 'GE'),
('Gestão de Projetos', 'GE'),
('Qualidade Total', 'GE'),
('Processos de Produção', 'GPI'),
('Planejamento e Controle da Produção', 'GPI'),
('Ergonomia e Segurança do Trabalho', 'GPI'),
('Administração da Produção', 'GPI');

-- Criação da tabela tb_categoria_faltas
CREATE TABLE tb_categoria_faltas (
    idcategoria INT AUTO_INCREMENT PRIMARY KEY,
    codcategoria VARCHAR(10) NOT NULL UNIQUE,
    categoria VARCHAR(255) NOT NULL
);

-- Inserção de dados na tabela tb_categoria_faltas
INSERT INTO tb_categoria_faltas (codcategoria, categoria)
VALUES 
('Cat01', 'Licença e Falta Médica'),
('Cat02', 'Falta Injustificada'),
('Cat03', 'Falta Justificada'),
('Cat04', 'Faltas Previstas na Legislação Trabalhista');

-- Criação da tabela tb_justificativa_faltas
CREATE TABLE tb_justificativa_faltas (
    idjustificativa INT AUTO_INCREMENT PRIMARY KEY,
    justificativa VARCHAR(255) NOT NULL,
    codcategoria VARCHAR(10),
    FOREIGN KEY (codcategoria) REFERENCES tb_categoria_faltas(codcategoria)
);

-- Inserção de dados na tabela tb_justificativa_faltas
INSERT INTO tb_justificativa_faltas (justificativa, codcategoria)
VALUES 
('Falta Médica', 'Cat01'),
('Comparecimento ao Médico', 'Cat01'),
('Licença-Saúde', 'Cat01'),
('Licença-Maternidade', 'Cat01'),
('Atraso ou Saída Antecipada', 'Cat02'),
('Atraso ou Saída Antecipada', 'Cat03'),
('Falecimento de cônjuge, pai, mãe, filho.', 'Cat04'),
('Falecimento ascendente, descendente, irmão ou pessoa dependente.', 'Cat04'),
('Casamento', 'Cat04'),
('Nascimento de filho', 'Cat04'),
('Acompanhar esposa em consultas médicas', 'Cat04'),
('Acompanhar filho em consulta médica', 'Cat04'),
('Doação voluntária de sangue', 'Cat04'),
('Alistamento como eleitor', 'Cat04'),
('Convocação para depoimento judicial', 'Cat04'),
('Comparecimento como jurado no Tribunal do Júri', 'Cat04'),
('Convocação para serviço eleitoral', 'Cat04'),
('Dispensa por nomeação para mesas receptoras nas eleições', 'Cat04'),
('Realização de Prova de Vestibular', 'Cat04'),
('Comparecimento na Justiça do Trabalho', 'Cat04'),
('Atrasos devido a acidentes de transporte', 'Cat04');

SELECT * FROM tb_categoria_faltas;
SELECT * FROM tb_cursos;
SELECT * FROM tb_disciplinas;
SELECT * FROM tb_justificativa_faltas;
SELECT * FROM tb_professores;