function openUserProfileModal() {
    document.getElementById('userProfileModal').style.display = 'block';
}

function closeUserProfileModal() {
    document.getElementById('userProfileModal').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('userProfileModal');
    var closeBtn = document.getElementsByClassName('close')[0];

    closeBtn.onclick = function() {
        closeUserProfileModal();
    };

    window.onclick = function(event) {
        if (event.target == modal) {
            closeUserProfileModal();
        }
    };
});
