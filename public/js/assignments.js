const textarea = document.getElementById('tickets');
const errors = document.getElementById('error-tickets');
const tickets_help = document.getElementById('tickets-help');


function prevForm(event) {
  validateTextarea();
  if (errors.innerHTML != "" || !confirm("¿Estás segur(a) de hacer la asignación?") ) {
    event.preventDefault();
  }
}


textarea.addEventListener('input', function(event) {
  const inputValue = event.target.value;

  for (let i = 0; i < inputValue.length; i++) {
    const charCode = inputValue.charCodeAt(i);
    if ((charCode < 48 || charCode > 57) && charCode !== 10) {
      event.target.value = inputValue.slice(0, i) + inputValue.slice(i + 1);
      return;
    }
  }

});

textarea.addEventListener('keydown', function(event) {
  
    if (event.key === 'Enter'  || event.key === 'Backspace'   || event.key === 'Delete') {
      validateTextarea();
      const sizeTa = tickets_help.scrollHeight;
      if(event.key === 'Enter'){
        textarea.style.height = (sizeTa + 30) + 'px';
      }else{
        textarea.style.height = (sizeTa - 10) + 'px';
      }
      
    }
});
  

function validateTextarea(){
  errors.innerHTML = "";
  
  const contenido = textarea.value;
  const lines = contenido.split('\n').filter(line => line.trim() !== '');
  const lastline = lines[lines.length - 1];

  if (/^\d+$/.test(lastline)) {
    const found = lines.slice(0, -1).indexOf(lastline);

    if (found !== -1) {
      errors.innerHTML = "<p>El número " + lastline + " ya ha sido ingresado en la línea " + (found + 1) + " </p>";
      const counter = Array.from({ length: lines.slice(0, -1).length }, (_, index) => index + 1);
      tickets_help.innerHTML = counter.join('<br>');
      textarea.value = lines.slice(0, -1).join('\n');
    } else {
      const counter = Array.from({ length: lines.length }, (_, index) => index + 1);
      tickets_help.innerHTML = counter.join('<br>');
    }
  } else {
    const counter = Array.from({ length: lines.slice(0, -1).length }, (_, index) => index + 1);
    tickets_help.innerHTML = counter.join('<br>');
  }
}

function clearList(){
  const numbers = document.getElementById('numbers');
  const regex = /\(([^)]+)\)/;
  const coincidencias = numbers.innerText.match(regex);
  const numbersArr = coincidencias ? coincidencias[1].split(',').map(Number) : [];

  const contenido = textarea.value;
  const lines = contenido.split('\n').filter(line => line.trim() !== '');
  const intValues = lines.map(line => parseInt(line, 10));

  textarea.value = compareArrays(numbersArr,intValues).join('\n');
  const message = document.querySelector('.alert-danger');
  message.remove();
}

function compareArrays(array1, array2) {
  let resultArray = [];
  for (let i = 0; i < array2.length; i++) {
    
    if(array1.indexOf(array2[i]) < 0){
      resultArray.push(array2[i]);
    }
  }

  return resultArray;
}