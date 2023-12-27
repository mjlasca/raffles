const tickets_pay = document.querySelector('.tickets-pay');
const ticket_row = `
                    <input type="number" name="ticket_number[]" class="ticket-number w-full border rounded-md py-2 px-3" placeholder="#Boleta" autocomplete="off" required>
                    <input type="number" name="ticket_payment[]" class="ticket-payment w-full border rounded-md py-2 px-3" placeholder="#Abono" autocomplete="off" required>    
                    <button type="button" class="bg-red-500 text-white  less px-3 rounded-md">-</button>
                `;
const button_more = document.querySelectorAll('.more');
const currentDomain = window.location.origin;
const delivery_id = document.getElementById('delivery_id');
const raffle_id = document.getElementById('raffle_id');
const user_id = document.getElementById('user_id');

button_more.forEach(button => {
    button.addEventListener('click', () => {
        const div = document.createElement('div');
        div.classList.add('row-ticket', 'flex', 'mb-2');
        div.innerHTML = ticket_row;
        tickets_pay.appendChild(div);
    });
});

tickets_pay.addEventListener('click', function(event) {
    const target = event.target;
    if (target.classList.contains('less')) {
        console.log("less");
        target.parentElement.remove();
    }
});


tickets_pay.addEventListener('input', function(event) {
    const target = event.target;

    if (target.classList.contains('ticket-number')) {
        if (target.value !== "") {
            // Obtener el siguiente elemento hermano (en este caso, el siguiente input)
            const siguienteInput = target.nextElementSibling;

            // Verificar si el siguiente elemento es un input
            if (siguienteInput && siguienteInput.classList.contains('ticket-payment')) {
                fetch(currentDomain+"/tickets/checkticket?number="+target.value+"&raffle_id="+raffle_id.value+"&user_id="+user_id.value)
                .then(response => {
                if (!response.ok) {
                    throw new Error(`Error de red - Código: ${response.status}`);
                }
            
                return response.json();
                })
                .then(data => {
                    console.log(data);
                    if(data != ''){
                        siguienteInput.placeholder = (data[0].price - data[0].payment);
                    }else{
                        alert("El número de boleta no pertenece a ésta usuario y rifa");
                        siguienteInput.placeholder = "#Abono";
                        target.value = "";
                    }
                })
                .catch(error => {
                console.error('Error en la solicitud:', error.message);
                });
                // Acceder al siguiente input
                
                
            }
        }
    }

    if (target.classList.contains('ticket-payment')) {
        if (target.value !== "") {
            // Obtener el siguiente elemento hermano (en este caso, el siguiente input)
            const prevInput = target.previousElementSibling;

            // Verificar si el siguiente elemento es un input
                
                fetch(currentDomain+"/tickets/checkticket?number="+prevInput.value+"&raffle_id="+raffle_id.value+"&user_id="+user_id.value)
                .then(response => {
                if (!response.ok) {
                    throw new Error(`Error de red - Código: ${response.status}`);
                }
            
                return response.json();
                })
                .then(data => {
                
                    if(data){
                       if(( data[0].price - data[0].payment ) < target.value){
                            alert("El valor que está ingresando sobrepasa el saldo de la boleta, el saldo es de "+( data[0].price - data[0].payment ));
                            target.placeholder = ( data[0].price - data[0].payment );
                            target.value = "";
                       }
                       if(calculateTotal() === false){
                            alert("La suma total ha superado el disponible");
                            target.value = "";
                       }
                    }
                })
                .catch(error => {
                console.error('Error en la solicitud:', error.message);
                });
                // Acceder al siguiente input
                
                
            
        }
    }
});


delivery_id.addEventListener('change', () => {

    const delivery_data = document.querySelector('.delivery-data');

    if(delivery_id.value !== ''){
        fetch(currentDomain+"/entregas/"+delivery_id.value+"?format=json")
        .then(response => {
          if (!response.ok) {
            throw new Error(`Error de red - Código: ${response.status}`);
          }
      
          return response.json();
        })
        .then(data => {
        console.log(data);
          delivery_data.innerHTML = `
                <p>${data.description}</p>
                <div class="flex">
                <p class="mr-2">Total entrega : ${data.total.toLocaleString()}</p>
                <p>Total usado : ${ data.used == null ? 0 : data.used.toLocaleString() }</p>
                </div>
                <p>Disponible : <span id="availible">${ (data.total - data.used).toLocaleString() }</span></p>
            `;
            if(user_id.value == ""){
                user_id.value = data.user_id;
            }
            raffle_id.value = data.raffle_id;
        })
        .catch(error => {
          console.error('Error en la solicitud:', error.message);
        });
    }else{
        delivery_data.innerHTML = "";
    }

});


    

function calculateTotal() {
    const inputsPayment = document.querySelectorAll('.ticket-payment');
    const availible = document.getElementById('availible');
    let totalInputs = 0;
    inputsPayment.forEach(element => {
        totalInputs += parseFloat(  element.value == "" ? 0 : element.value );
    });
    console.log(totalInputs + "  / "+availible.innerText);
    if(totalInputs > parseFloat( availible.innerText.replace('.','')))
        return false;
    
    return true;
    
}