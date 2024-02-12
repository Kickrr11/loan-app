<template>
  <highcharts :options="chartOptions"></highcharts>
</template>

<script setup>
const props = defineProps({
  monthlyLoanDistributions: Object,
});

const chartOptions = {
  yAxis: {
    title: {
      text: 'Number of Loans'
    }
  },
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
  series: [{
    name: 'Monthly Loan Disbursement',
    data: props.monthlyLoanDistributions.map(entry => parseFloat(entry.amount))
}],
};
console.log(chartOptions.xAxis.categories);


</script>

<style scoped>

</style>