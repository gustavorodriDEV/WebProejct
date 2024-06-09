document.addEventListener('DOMContentLoaded', function () {
    // Função para formatar a biografia
    function formatBio(bio) {
    let formattedBio = '';
    let length = 30;

    while (bio.length > 0) {
        if (bio.length > length) {
            let chunk = bio.substring(0, length);
            let spaceIndex = chunk.lastIndexOf(' ');
            let cutIndex = spaceIndex > -1 ? spaceIndex : length;
            formattedBio += chunk.substring(0, cutIndex).trim() + '\n';
            bio = bio.substring(cutIndex).trim();
        } else {
            formattedBio += bio.trim();
            break;
        }
    }
    return formattedBio;
}

    // Modal de Informações do Perfil
    var infoModal = document.getElementById("infoModal");
    var btnOpenInfoModal = document.getElementById("openModalButton");
    var btnCloseInfoModal = document.getElementsByClassName("close")[0];

    btnOpenInfoModal.onclick = function () {
        infoModal.style.display = "block";
    };

    btnCloseInfoModal.onclick = function () {
        infoModal.style.display = "none";
    };

    window.onclick = function (event) {
        if (event.target == infoModal) {
            infoModal.style.display = "none";
        }
    };

    // Aplicar a formatação na biografia ao carregar a página
    let bioText = document.getElementById('userBioDisplay').textContent;
    document.getElementById('userBioDisplay').textContent = formatBio(bioText);

    // Submissão de formulário para atualizar biografia
    var bioForm = document.querySelector('form[action="updatePerfil.php"]');
    bioForm.onsubmit = function (event) {
        event.preventDefault();
        var bio = document.getElementById("bio").value;
        if (bio.length > 200) {
            // Se a biografia excede 200 caracteres, mostra a mensagem de erro
            document.getElementById("bioErrorMessage").textContent = "Desculpe, a sua biografia não pode exceder 200 caracteres. Por favor, reduza o tamanho e tente novamente.";
            document.getElementById("bioErrorMessage").style.display = 'block';
        } else {
            // Se estiver tudo certo, oculta a mensagem de erro e submete o formulário
            document.getElementById("bioErrorMessage").style.display = 'none';
            bioForm.submit();
        }
    };

    // Modal de Perfil do Usuário
    var userProfileModal = document.getElementById('userProfileModal');
    var closeUserProfileModalBtn = document.getElementsByClassName('close')[1]; // Supondo que seja o segundo botão 'close'

    closeUserProfileModalBtn.onclick = function () {
        userProfileModal.style.display = "none";
    };

    window.onclick = function (event) {
        if (event.target == userProfileModal) {
            userProfileModal.style.display = "none";
        }
    };
});


function hideMessage(id) {
    var element = document.getElementById(id);
    if (element) {
        setTimeout(function() {
            element.style.display = 'none'; // Oculta a mensagem
        }, 5000); // Tempo em milissegundos (5000 ms = 5 segundos)
    }
}

// Chame esta função para cada mensagem que você deseja ocultar automaticamente
hideMessage('errorMessage');
hideMessage('successMessage');
