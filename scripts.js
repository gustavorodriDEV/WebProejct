document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById("infoModal");
    var btnOpenModal = document.getElementById("openModalButton");
    var btnCloseModal = document.getElementsByClassName("close")[0];
    var bioForm = document.querySelector('form[action="updatebio.php"]'); // Certifique-se de que este seletor corresponde ao seu formulário

    // Abrir o modal ao clicar no 'Alterar'
    btnOpenModal.onclick = function () {
        modal.style.display = "block";
    };

    // Fechar o modal ao clicar no 'X'
    btnCloseModal.onclick = function () {
        modal.style.display = "none";
    };

    // Fechar o modal ao clicar fora dele
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    // Manipular submissão do formulário dentro do modal para atualizar biografia
    document.getElementById("submitInfo").onclick = function (event) {
        event.preventDefault(); // Impede o recarregamento da página
        var bio = document.getElementById("bio").value;

        // Verifica se a biografia tem 30 caracteres ou menos
        if (bio.length <= 30) {
            bioForm.submit(); // Submete o formulário para "updatebio.php"
        } else {
            alert("A biografia não deve exceder 30 caracteres.");
        }
    };
});
