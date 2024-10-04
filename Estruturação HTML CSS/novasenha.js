document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    const newPasswordInput = document.getElementById("new-password");
    const confirmPasswordInput = document.getElementById("confirm-password");
    const errorMessage = document.getElementById("error-message");

    form.addEventListener("submit", function(event) {
        errorMessage.textContent = ""; // Limpa mensagens de erro
        let valid = true; // Indica se o formulário é válido

        // Limpa classes de erro antes da validação
        newPasswordInput.classList.remove("error");
        confirmPasswordInput.classList.remove("error");

        // Verifica se a nova senha está vazia
        if (newPasswordInput.value.trim() === "") {
            errorMessage.textContent = "O campo de nova senha não pode estar vazio."; // Exibe a mensagem de erro
            newPasswordInput.classList.add("error"); // Adiciona contorno vermelho
            valid = false;
        } else if (confirmPasswordInput.value.trim() === "") {
            // Verifica se a confirmação de senha está vazia
            errorMessage.textContent = "O campo de confirmação da nova senha não pode estar vazio."; // Exibe a mensagem de erro
            confirmPasswordInput.classList.add("error"); // Adiciona contorno vermelho
            valid = false;
        } else if (newPasswordInput.value !== confirmPasswordInput.value) {
            // Verifica se a nova senha e a confirmação coincidem
            errorMessage.textContent = "As senhas não coincidem."; // Exibe a mensagem de erro
            newPasswordInput.classList.add("error"); // Adiciona contorno vermelho
            confirmPasswordInput.classList.add("error"); // Adiciona contorno vermelho
            valid = false;
        }

        // Se houver erro, previne o envio do formulário
        if (!valid) {
            event.preventDefault();
        }
    });
});

