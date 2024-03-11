const detail_commission = document.querySelector('.detail--ticket');
const input_select = document.getElementById('user_id');

input_select.addEventListener('change', function(event){
  const butt = document.querySelector('.button--submit');
  const clear_detail = detail_commission.querySelectorAll('[data-id]');

  if(butt){
    if(butt.classList.contains('hidden')){
      butt.classList.remove('hidden');
    }else{
      butt.classList.add('hidden');
    }
  }

  clear_detail.forEach(element => {
    if(!element.classList.contains('hidden'))
      element.classList.add('hidden');
  });

  const div_detail = detail_commission.querySelector(`[data-id="${this.value}"]`);
  if(div_detail.classList.contains('hidden'))
    div_detail.classList.remove('hidden');

});


function confirmCommission(text = "¿Está segur@ de realizar el registro de comisión?"){
  return confirm(text);
}