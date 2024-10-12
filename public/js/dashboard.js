
const chart_raffle = document.querySelectorAll('.chart-raffle');

chart_raffle.forEach(ch => {
  const canvaChart = ch.querySelectorAll('canvas');
  const total_raffle = ch.querySelector('[data-total-total-raffle]');
  const total_delivery = ch.querySelector('[data-total-delivery]');
  setChart(canvaChart,total_raffle.getAttribute('data-total-total-raffle'),total_delivery.getAttribute('data-total-delivery'));
});


function setChart(ctx, total_raffle, total_delivery){
  let perceDeli = 0;
  let perceRaff = 0;
  if(total_raffle && total_delivery){
    perceDeli = ( total_delivery / total_raffle ) * 100;
    perceRaff = 100 - perceDeli;
  }
  new Chart(ctx, {
    type: 'pie',
    data: {
      labels: ['Porcentaje entregas', 'Porcentaje saldo rifa'],
      datasets: [{
        data: [perceDeli.toFixed(2), perceRaff.toFixed(2)],
        borderWidth: 1
      }]
    },
    
  });
}
