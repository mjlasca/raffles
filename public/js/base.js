const nav_ = document.querySelector('.hamburguer');
const close_nav = document.querySelector('.close-nav');
const raffle_sidebar = document.querySelector('.sidebar-raffle');
const pagination = document.querySelector('.pagination');
console.log("testtt");
if(close_nav){
  close_nav.addEventListener('click', function() {
    if(raffle_sidebar){
      raffle_sidebar.classList.add('hidden');
    }
  });
}

if(nav_){
  nav_.addEventListener('click', function() {
    if(raffle_sidebar){
      raffle_sidebar.classList.remove('hidden');
    }
  });
}

if(raffle_sidebar){
  raffle_sidebar.addEventListener('click', function() {
    console.log('Se hizo clic en el elemento');
  });
}

if(pagination){
  const urlParams = new URLSearchParams(window.location.search);
  const paramsString = urlParams.toString().replace('page','pageprev');
  const links = pagination.querySelectorAll('a');
  links.forEach(link => {
    console.log(link.href);
    console.log(paramsString);
    link.href = link.href + "&" + paramsString;
  });
}

$(document).ready(function() {
  $('#user_id').select2();
});