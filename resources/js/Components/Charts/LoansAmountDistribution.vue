<template>
  <highcharts :options="chartOptions" :callback="someFunction"></highcharts>
</template>

<script setup>
  import {ref} from "vue";

  const props = defineProps({
    loansAmountDistributions: Object,
  });

  const someFunction = (chart) => {
    console.log(chart);
  }

  const chartOptions = ref({
    chart: {
      type: "bar",
    },
    title: {
      text: "Loan Amount Distributions",
    },

    xAxis: {
      categories: Object.keys(props.loansAmountDistributions),
      title: {
        text: "Loan Amount Range",
      },
    },
    exporting: {
      buttons: {
        contextButton: {
          menuItems: [
            {
              text: 'Custom Export',
              onclick: function () {
                console.log('heree');
                // Your custom code for exporting
              },
            },
          ],
        },
      },
    },
    yAxis: {
      min: 0,
      title: {
        text: "Number of Loans",
      },
    },
    legend: {
      reversed: true,
    },
    plotOptions: {
      series: {
        stacking: "arearange",
      },
    },
    series: [
      {
        name: "Number of Loans",
        data: Object.values(props.loansAmountDistributions),
        color: "#6fcd98", // Bar color
      },
    ],
  });
  console.log(Object.values(props.loansAmountDistributions));
</script>

<style scoped>

</style>