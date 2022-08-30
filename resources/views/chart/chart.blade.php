<div>
    <canvas id="myChart"></canvas>
</div>
<script src="{{ asset('js/chart.min.js') }}"></script>
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

    const myChart = new Chart(
        document.getElementById('myChart'),
        config
    );
  </script>