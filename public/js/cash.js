const manualMoney = document.querySelector('input[name="manual_money_box"]');

if(manualMoney){
  const real_money_box = document.querySelector('input[name="real_money_box"]');
  const difference = document.querySelector('input[name="difference"]');
  manualMoney.addEventListener('input', function(event){
    const real = parseFloat(real_money_box.value);
    console.log(real);
    difference.value = "";
    if(this.value != ""){
      difference.value = parseFloat( manualMoney.value ) - real;
    }
  });
}

function confirmM(text){
  return confirm(text);
}