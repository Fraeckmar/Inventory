
<canvas id="canvas" height="300" width="300"></canvas>
<div id="stocks-wrap" style="position: relative; height:500px; width:500px">
  <canvas id="stocks" height="300" width="300"></canvas>
</div>

<script src="{{ asset('js/chart/chart.min.js') }}"></script>
<script src="{{ asset('js/chart/chartjs-plugin-doughnutlabel.min.js') }}"></script>
<script>

    const labels = [
      'WEEK 1',
      'WEEK 2',
      'WEEK 3',
      'WEEK 4'
    ];
  
    const data = {
      labels: labels,
      datasets: [{
        label: 'My First dataset',
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgb(255, 99, 132)',
        data: [35, 15, 45, 60, 100],
      }]
    };
  
    const config = {
      type: 'bar',
      data: data,
      options: {
        plugins: {
            title: {
                display: true,
                text: 'my Title'
            },
            legend: {
                display: false
            },
        }
      }
    };

    const stock_label = [
      'INBOUND',
      'OUTBOUND'
    ];
    const stock_data = {
      labels : stock_label,
      datasets: [{
        label: 'ITEM 1',
        backgroundColor: [
          '#ffd9b3',
          '#ccff66'
        ],
        data: [30, 70]
      }]
    };
    const stock_config = {
      type: 'doughnut',
      data: stock_data,
      options: {
        cutoutPercentage: 80,
        responsive: true,
        plugins: {          
          // datalabels: {
          //   display: true,
          //   backgroundColor: '#ccc',
          //   borderRadius: 3,
          //   font: {
          //     color: 'red',
          //     weight: 'bold',
          //   }
          // },
          doughnutlabel: {
            labels: [{
              text: '550',
              font: {
                size: 20,
                weight: 'bold'
              }
            }, {
              text: 'total'
            }]
          }
        }
      }
    };

    const myChart = new Chart(
        document.getElementById('stocks'),
        stock_config
    );
  </script>