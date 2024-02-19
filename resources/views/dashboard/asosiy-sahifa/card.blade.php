

<div class="w-full max-w-xl bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <div class="flex justify-end px-4 pt-8">
     
      
        
    </div>
    <div class="flex flex-col items-center pb-3">
        <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="{{'/storage/users/image'}}/{{$auth->image}}" alt="Bonnie image"/>
        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $auth->second_name}} {{ $auth->first_name}}</h5>
        <span class="text-sm text-gray-500 dark:text-gray-400">#ID {{$auth->employee_id_number}}</span>
      
    </div>

    <div class=" w-full bg-white rounded-lg shadow dark:bg-gray-800 px-4 pt-1">
        
        <!-- Line Chart -->
        <div class="py-6" id="pie-chart"></div>
      
        <div class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">
          <div class="flex justify-between items-center pt-5">
            <!-- Button -->
          
           
         
          </div>
        </div>
      </div>

</div>




  <script>
    
const getChartOptions = () => {
  return {
    series: [52.8, 26.8, 20.4],
    colors: ["#1C64F2", "#16BDCA", "#9061F9"],
    chart: {
      height: 420,
      width: "100%",
      type: "pie",
    },
    stroke: {
      colors: ["white"],
      lineCap: "",
    },
    plotOptions: {
      pie: {
        labels: {
          show: true,
        },
        size: "100%",
        dataLabels: {
          offset: -25
        }
      },
    },
    labels: ["Direct", "Organic search", "Referrals"],
    dataLabels: {
      enabled: true,
      style: {
        fontFamily: "Inter, sans-serif",
      },
    },
    legend: {
      position: "bottom",
      fontFamily: "Inter, sans-serif",
    },
    yaxis: {
      labels: {
        formatter: function (value) {
          return value + "%"
        },
      },
    },
    xaxis: {
      labels: {
        formatter: function (value) {
          return value  + "%"
        },
      },
      axisTicks: {
        show: false,
      },
      axisBorder: {
        show: false,
      },
    },
  }
}

if (document.getElementById("pie-chart") && typeof ApexCharts !== 'undefined') {
  const chart = new ApexCharts(document.getElementById("pie-chart"), getChartOptions());
  chart.render();
}

  </script>