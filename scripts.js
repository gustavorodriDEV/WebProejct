
var modal = document.getElementById("infoModal");

var btn = document.getElementById("openModalButton");


var span = document.getElementsByClassName("close")[0];


btn.onclick = function() {
  modal.style.display = "block";
}


span.onclick = function() {
  modal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}


document.getElementById("submitInfo").onclick = function() {
  var name = document.getElementById("modalName").value;
  var description = document.getElementById("modalDescription").value;
  
  // Atualiza o texto dos elementos
  var userNameDisplay = document.getElementById("userNameDisplay");
  var userDescriptionDisplay = document.getElementById("userDescriptionDisplay");
  
  userNameDisplay.textContent = name;
  userDescriptionDisplay.textContent = description;
  
  
  userNameDisplay.classList.remove("placeholder-text");
  userDescriptionDisplay.classList.remove("placeholder-text");
  
 
  modal.style.display = "none";
}

// foto de perfil do Usuario

function atualizarPerfilEPostagens(input) {
  if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function(e) {
          // Assume que você tenha uma tag <img> com a classe .avatar-icon para a imagem de perfil
          var avatarIcon = document.querySelector('.avatar-icon');
          if (avatarIcon.tagName === 'IMG') {
              avatarIcon.src = e.target.result; // Atualiza a src para a imagem carregada
          } else {
              // Se for um ícone do FontAwesome, altere para usar uma <img>
              console.error('Avatar icon is not an <img> element.');
          }

          // Opcional: Atualiza as imagens de perfil nos cartões de postagem
          document.querySelectorAll('.imagem-perfil').forEach(function(img) {
              img.src = e.target.result;
          });
      };

      reader.readAsDataURL(input.files[0]); // Lê a imagem selecionada pelo usuário
  }
}

function toggleAvatarCarousel() {
  const carousel = document.querySelector('.carousel-container');
  carousel.style.display = carousel.style.display === 'none' ? 'block' : 'none';
}

document.getElementById('avatarCarousel').addEventListener('click', function(event) {
  const target = event.target;
  if (target.tagName === 'IMG') {
      var avatarIcon = document.querySelector('.avatar-icon');
      if (avatarIcon.tagName === 'IMG') {
          avatarIcon.src = target.src; // Atualiza a imagem de perfil com a selecionada no carrossel
      } else {
          // Se for necessário converter um ícone FontAwesome em <img>, este é o lugar para o ajuste
          console.error('Avatar icon is not an <img> element.');
      }
      toggleAvatarCarousel(); // Esconde o carrossel após a seleção
  }
});

// BARRAR VISUALIZAÇÃO DA POSTAGEM: 

document.addEventListener('DOMContentLoaded', function() {
    // Suponha que essa função verifica se o usuário está logado e retorna true ou false.
    // Você precisará implementar a lógica de verificação de login de acordo com sua aplicação.
    let usuarioLogado = checarSeUsuarioEstaLogado();

    if (!usuarioLogado) {
        // Se o usuário NÃO está logado, oculte o link de postagem
        document.getElementById('linkPostagem').style.display = 'none';
    }
    // Se o usuário está logado, o link de postagem permanecerá visível.
});

function checarSeUsuarioEstaLogado() {
    // Aqui você deve implementar a lógica para verificar se o usuário está logado.
    // Esta função é apenas um placeholder e deve retornar true se o usuário estiver logado,
    // e false caso contrário. 
    // Exemplo:
    // return localStorage.getItem('usuarioLogado') === 'true';
}




