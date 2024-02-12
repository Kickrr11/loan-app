<template>
  <highcharts :options="chartOptions"></highcharts>
</template>

<script setup>
const props = defineProps({
  monthlyLoanDistributions: Object,
});

const chartOptions = {
  yAxis: [
    {
      title: {
        text: 'Amount',
      },
    },
    {
      title: {
        text: 'Count',
      },
      opposite: true,
    },
  ],
  xAxis: {
    categories: props.monthlyLoanDistributions.map(entry => `${entry.month} ${entry.year}`),
    title: {
      text: 'Months'
    }
  },
  plotOptions: {
    series: {
      label: {
        connectorAllowed: false
      }
    }
  },
  series: [
    {
      name: 'Monthly Loan Amount',
      data: props.monthlyLoanDistributions.map(entry => parseFloat(entry.amount)),
      type: 'column',
      yAxis: 0,
    },
    {
      name: 'Monthly Loan Count',
      data: props.monthlyLoanDistributions.map(entry => entry.loans_count),
      type: 'line',
      yAxis: 1,
    }
  ],
};
</script>

<style scoped>

</style>