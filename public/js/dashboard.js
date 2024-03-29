
const chart_raffle = document.querySelectorAll('.chart-raffle');

chart_raffle.forEach(ch => {
  const canvaChart = ch.querySelectorAll('canvas');
  const total_raffle = ch.querySelector('[data-total-total-raffle]');
  const total_delivery = ch.querySelector('[data-total-delivery]');
  setChart(canvaChart,total_raffle.getAttribute('data-total-total-raffle'),total_delivery.getAttribute('data-total-delivery'));
});


function setChart(ctx, total_raffle, total_delivery){
  console.log(ctx);
  console.log(total_raffle);
  console.log(total_delivery);
  //const ctx = document.getElementById(id);
  new Chart(ctx, {
    type: 'pie',
    data: {
      datasets: [{
        label: ['Entregas','Total rifa'],
        data: [total_delivery, total_raffle],
        borderWidth: 1
      }]
    },
    
  });
}
