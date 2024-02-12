<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import {ref, onMounted} from "vue";
import * as Dashboards from '@highcharts/dashboards/dashboards';
import UsersLoans from '@/Components/Charts/UsersLoans.vue';
import LoansAmountDistribution from '@/Components/Charts/LoansAmountDistribution.vue';
import MonthlyLoanDisbursement from "@/Components/Charts/MonthlyLoanDisbursement.vue";

const props = defineProps({
  usersLoans: Object,
  loansAmountDistributions: Object,
  monthlyLoanDistributions: Object,
});

const chartOptions = ref({
  points: [10, 0, 8, 2, 6, 4, 5, 5],
  chartType: 'Spline',
  seriesColor: '#6fcd98',
  colorInputIsSupported: null,
  chart: {
    type: 'spline',
    backgroundColor: '#f5f5f5', // Set background color
    height: 500, // Set chart height
    margin: [10, 10, 30, 10], // Set margins [top, right, bottom, left]
    spacing: [10, 10, 10, 10], // Set spacing [top, right, bottom, left]
    borderRadius: 8, // Set border radius
    events: {
      load: function () {
        // Custom onLoad event
      }
    }
  },
  title: {
    text: 'Customized Spline Chart',
    style: {
      color: '#333', // Set title text color
      fontSize: '18px', // Set title font size
      fontWeight: 'bold' // Set title font weight
    }
  },
  xAxis: {
    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
    title: {
      text: 'Months',
      style: {
        color: '#666' // Set xAxis title color
      }
    },
    labels: {
      style: {
        color: '#666' // Set xAxis labels color
      }
    }
  },
  yAxis: {
    title: {
      text: 'Values',
      style: {
        color: '#666' // Set yAxis title color
      }
    },
    labels: {
      style: {
        color: '#666' // Set yAxis labels color
      }
    }
  },
  legend: {
    enabled: true,
    align: 'right',
    verticalAlign: 'top',
    itemStyle: {
      color: '#333' // Set legend item color
    }
  },
  series: [{
    name: 'Sales',
    data: [10, 0, 8, 2, 6, 4, 5, 5, 3],
    color: '#6fcd98'
  }],
});

</script>

<template>
  <AppLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="chart-container">
            <UsersLoans :usersLoans="usersLoans"></UsersLoans>
            <LoansAmountDistribution :loansAmountDistributions="loansAmountDistributions"/>
            <MonthlyLoanDisbursement :monthlyLoanDistributions/>
            <!--
            <highcharts :options="chartOptions"></highcharts>
            -->
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
<style scoped>
/*
.chart-container {
  display: flex;
  justify-content: space-between;
}
*/
</style>
