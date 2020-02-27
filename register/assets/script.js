document.addEventListener('DOMContentLoaded', function(e){

  let arenaForm = document.getElementById('arena-registration');
  let container = document.getElementById('contain');

  arenaForm.addEventListener('submit', (e) => {
    e.preventDefault();

    let data = {
      first_name: arenaForm.querySelector('[name="first_name"]').value,
      last_name: arenaForm.querySelector('[name="last_name"]').value,
      birthday: arenaForm.querySelector('[name="birthday"]').value,
      gender: arenaForm.querySelector('[name="gender"]').value,
      email: arenaForm.querySelector('[name="email"]').value,
      password: arenaForm.querySelector('[name="password"]').value,
      skills: arenaForm.querySelector('[name="skills"]').value
    };

    let url = arenaForm.dataset.url;
    let params = new URLSearchParams(new FormData(arenaForm));

    fetch(url, {
      method: "POST",
      body: params
    }).then(res => res.json());

    contain.innerHTML = "<h1 class='user_registered'>User Registered</h1>";

  });
});
