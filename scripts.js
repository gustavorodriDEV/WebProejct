document.addEventListener('DOMContentLoaded', function () {
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

    // Submissão de formulário para atualizar biografia
    var bioForm = document.querySelector('form[action="updatebio.php"]');
    document.getElementById("submitInfo").onclick = function (event) {
        event.preventDefault();
        var bio = document.getElementById("bio").value;
        if (bio.length <= 100) {
            bioForm.submit(); // Submete o formulário para "updatebio.php"
        } else {
            alert("A biografia não deve exceder 100 caracteres.");
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

function openUserProfileModal(username, bio, profileImage, joinedDate, accountAge) {
    document.getElementById('modalUsername').textContent = username;
    document.getElementById('modalBio').textContent = bio;

    if (profileImage && profileImage !== 'null' && profileImage !== '') {
        document.getElementById('profileImage').src = profileImage;
        document.getElementById('profileImage').style.display = 'block';
        document.getElementById('defaultAvatar').style.display = 'none';
    } else {
        document.getElementById('profileImage').style.display = 'none';
        document.getElementById('defaultAvatar').style.display = 'block';
    }

    document.getElementById('modalJoinedDate').textContent = joinedDate;
    document.getElementById('modalAccountAge').textContent = accountAge;

    document.getElementById('userProfileModal').style.display = 'block';
}

function closeUserProfileModal() {
    document.getElementById('userProfileModal').style.display = 'none';
}
