<template>
  <highcharts :options="chartOptions"></highcharts>
</template>

<script setup>

import {ref} from "vue";

const props = defineProps({
  usersLoans: Object,
});
const chartOptions = ref({
  chartType: 'Pie',
  seriesColor: '#6fcd98',
  colorInputIsSupported: null,
  chart: {
    type: 'pie',
    backgroundColor: '#f5f5f5',
    height: 500,
    margin: [30, 10, 30, 10],
    spacing: [10, 10, 10, 10],
    borderRadius: 8,
    events: {
      load: function () {
        // Custom onLoad event
      }
    }
  },
  showInLegend: true,
  title: {
    text: 'Users Loans Distribution',
    style: {
      color: '#333',
      fontSize: '15px',
      fontWeight: 'bold',
    },
  },

  legend: {
    enabled: true,
    align: 'left',
    verticalAlign: 'bottom',
    itemStyle: {
      color: '#333'
    }
  },
  series: [{
    name: 'Users Loans',
    allowPointSelect: true,
    colorByPoint: true,
    data: props.usersLoans,
    dataLabels: {
      enabled: true,
      format: '{point.name}: {point.percentage:.1f}%'
    },
    color: '#6fcd98',
    showInLegend: true,
  }],
  tooltip: {
    headerFormat: '',
    pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y}</b><br/>',
  },
});

</script>

<style scoped>

</style>