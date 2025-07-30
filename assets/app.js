import './bootstrap.js';
Turbo.session.drive = false;
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

window.showTab = function (tab, event) {
  document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
  document.querySelectorAll('.tab-button').forEach(el => {
    el.classList.remove('text-blue-400', 'border-b-2', 'border-blue-500', 'font-semibold');
    el.classList.add('text-gray-400');
  });
  const content = document.getElementById('tab-' + tab);
  if (content) content.classList.remove('hidden');
  if (event && event.target) {
    event.target.classList.add('text-blue-400', 'border-b-2', 'border-blue-500', 'font-semibold');
    event.target.classList.remove('text-gray-400');
  }
};

document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById('editProfileModal');
  const openBtn = document.getElementById('editProfileBtn');

  const openModal = () => {
    if (modal) {
      modal.classList.remove("hidden");
      document.body.style.overflow = "hidden";
    }
  }

  const closeModal = () => {
    if (modal) {
      modal.classList.add("hidden");
      modal.display.style = "none";
      document.body.style.overflow = "auto";
      modal.offsetHeight;
    }
  }

  if (openBtn) {
    openBtn.addEventListener("click", openModal);
  }

  if (modal) {
    modal.addEventListener("click", e => {
      if (e.target === modal) {
        closeModal();
      }
    })
  }

  document.addEventListener('keydown', e => {
    if (e.key === "Escape" && modal && !modal.classList.contains("hidden")) {
      closeModal();
    }
  })

})

document.addEventListener('DOMContentLoaded', function () {
  var buttons = document.querySelectorAll('.comment-toggle');
  if (!buttons.length) {
    console.log('Aucun bouton .comment-toggle trouvé');
    return;
  }
  buttons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      const targetSelector = btn.getAttribute('data-target');
      const target = document.querySelector(targetSelector);
      if (target) {
        target.classList.toggle('hidden');
        const svg = btn.querySelector('svg');
        if (svg) {
          svg.classList.toggle('rotate-180');
        }
      }
    });
  });
});


document.addEventListener('DOMContentLoaded', function() {
    const openBtn = document.getElementById('openTweetModalBtn');
    const modal = document.getElementById('tweetModal');
    const closeBtn = document.getElementById('closeModalBtn');

    if (openBtn && modal) {
        openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
    }
    if (closeBtn && modal) {
        closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
    }
    
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });
});

// MENU BURGER

document.getElementById('openProfileMenu').addEventListener('click', function(e){
    e.preventDefault();
    document.getElementById('profile-menu').classList.remove('-translate-x-full');
    document.getElementById('profile-menu-backdrop').classList.remove('hidden');
});
document.getElementById('closeProfileMenu').addEventListener('click', function(){
    document.getElementById('profile-menu').classList.add('-translate-x-full');
    document.getElementById('profile-menu-backdrop').classList.add('hidden');
});
document.getElementById('profile-menu-backdrop').addEventListener('click', function(){
    document.getElementById('profile-menu').classList.add('-translate-x-full');
    document.getElementById('profile-menu-backdrop').classList.add('hidden');
});

// AUTRES

document.querySelector('input[type=file][name$="[avatar]"]').addEventListener('change', function (e) {
  if (e.target.files && e.target.files[0]) {
    document.getElementById('avatarImg').src = URL.createObjectURL(e.target.files[0]);
  }
});

document.getElementById('avatarImg').onclick = function () {
  document.querySelector('input[type=file][name$="[avatar]"]').click();
};