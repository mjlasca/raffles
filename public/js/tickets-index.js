
const checks_all = document.querySelector('.check-removable_all');
const action_checks = document.querySelector('.action-checks');
if(checks_all){
  checks_all.addEventListener('change', function(){
    const checks = document.querySelectorAll('.check-removable');
    if(checks){
      checks.forEach(check => {
        check.checked = checks_all.checked;
      });
    }
    
  });
}

if(action_checks){
  action_checks.addEventListener('click', async function(event) {
    const checks = document.querySelectorAll('.check-removable');
    const csrf = document.querySelector('input[name="_token"]');
    if(checks){
      const formdata = new FormData();
      formdata.append('_token',csrf.value)
      let arrch  = [];
      checks.forEach(check => {
        if(check.checked){
          arrch.push(check.value);
        }
      });
      if(arrch.length > 0){
        formdata.append('checks',arrch);
        let response = await fetch('/tickets/removable', {
          method: 'POST',
          body: formdata
        });
    
        let result = await response.json()
      }else{
        alert('No hay nada seleccionado');
      }
    }else{
      alert('No hay nada seleccionado');
    }
  });
}






