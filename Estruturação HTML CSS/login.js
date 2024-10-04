document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    const loginInput = document.getElementById("login");
    const passwordInput = document.getElementById("password");
    const errorMessage = document.getElementById("error-message");

    form.addEventListener("submit", function(event) {
        errorMessage.textContent = ""; // Limpa mensagens de erro
        let valid = true; // Indica se o formulário é válido

        // Limpa classes de erro antes da validação
        loginInput.classList.remove("error");
        passwordInput.classList.remove("error");

        // Verifica se o login está vazio
        if (loginInput.value.trim() === "") {
            errorMessage.textContent = "O campo de login não pode estar vazio."; // Exibe a mensagem de erro
            loginInput.classList.add("error"); // Adiciona contorno vermelho
            valid = false;
        } else if (!/^\d{5}$/.test(loginInput.value)) {
            // Se o login não estiver vazio, verifica se é um número de 5 dígitos
            errorMessage.textContent = "O login deve conter exatamente 5 dígitos."; // Exibe a mensagem de erro
            loginInput.classList.add("error"); // Adiciona contorno vermelho
            valid = false;
        } else if (passwordInput.value.trim() === "") {
            // Verifica se a senha está vazia
            errorMessage.textContent = "O campo de senha não pode estar vazio."; // Exibe a mensagem de erro
            passwordInput.classList.add("error"); // Adiciona contorno vermelho
            valid = false;
        }

        // Se houver erro, previne o envio do formulário
        if (!valid) {
            event.preventDefault();
        }
    });
});


