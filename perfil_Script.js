document.addEventListener('DOMContentLoaded', function () {
    var profileLink = document.querySelector('.nav-item[href="perfil.php"]');
    var perfilModal = document.getElementById('perfilModal');

    profileLink.addEventListener('click', function(e) {
        e.preventDefault(); // Prevenir ação padrão do link
        perfilModal.style.display = 'block';
    });

    var closeBtn = document.querySelector('.modal .close');
    closeBtn.addEventListener('click', function() {
        perfilModal.style.display = 'none';
    });

    // Fechar o modal ao clicar fora dele
    window.onclick = function(event) {
        if (event.target == perfilModal) {
            perfilModal.style.display = 'none';
        }
    };
});
