function validarCPF(cpf) {
    cpf = cpf.replace(/[^\d]+/g,'');
    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
        alert("CPF inválido!");
        return false;
    }
    let soma = 0, resto;
    for (let i = 1; i <= 9; i++) soma += parseInt(cpf.substring(i-1, i)) * (11 - i);
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.substring(9, 10))) {
        alert("CPF inválido!");
        return false;
    }
    soma = 0;
    for (let i = 1; i <= 10; i++) soma += parseInt(cpf.substring(i-1, i)) * (12 - i);
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.substring(10, 11))) {
        alert("CPF inválido!");
        return false;
    }
    return true;
}

function buscarCEP() {
    let cep = document.getElementById('cep').value.replace(/\D/g,'')};
    if (cep !== "") {
        let url = "buscar_cep.php?cep=" + cep;
        fetch(url)
            .then(response => response.json())
            .then(data =>'')}
                if (!data.erro) {
                    document.getElementById('endereco').value = data.logradouro;
                    document.getElementById('cidade').value = data.localidade;
                    document.getElementById('es')};
