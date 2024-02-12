<template>
  <highcharts :options="chartOptions"></highcharts>
</template>

<script setup>
import {ref} from "vue";

const props = defineProps({
  loansByPeriodAnalisys: Object,
});

const chartOptions = ref({
  chart: {
    type: 'pie',
  },
  series: [{
    name: 'Loan Periods',
    allowPointSelect: true,
    colorByPoint: true,
    data: props.loansByPeriodAnalisys,

    dataLabels: {
      enabled: true,
      format: '{point.name}: {point.percentage:.1f}%'
    },
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