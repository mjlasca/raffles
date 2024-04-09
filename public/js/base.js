const nav_ = document.querySelector('.hamburguer');
const close_nav = document.querySelector('.close-nav');
const raffle_sidebar = document.querySelector('.sidebar-raffle');

if(nav_){
  
  

}

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
