document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById("infoModal");
    var btnOpenModal = document.getElementById("openModalButton");
    var btnCloseModal = document.getElementsByClassName("close")[0];

    // Abrir o modal
    btnOpenModal.onclick = function () {
        modal.style.display = "block";
    }

    // Fechar o modal ao clicar no 'X'
    btnCloseModal.onclick = function () {
        modal.style.display = "none";
    }

    // Fechar o modal ao clicar fora dele
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Manipular submissão do formulário dentro do modal
    document.getElementById("submitInfo").onclick = function (event) {
        event.preventDefault();  // Impede o recarregamento da página

        var description = document.getElementById("modalDescription").value;

        // Atualiza o texto do elemento de descrição
        var userDescriptionDisplay = document.getElementById("userDescriptionDisplay");

        userDescriptionDisplay.textContent = description;
        userDescriptionDisplay.classList.remove("placeholder-text");

        // Fecha o modal após a atualização
        modal.style.display = "none";
    }
});
