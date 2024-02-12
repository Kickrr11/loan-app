<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import {ref, onMounted} from "vue";
import * as Dashboards from '@highcharts/dashboards/dashboards';
import UsersLoans from '@/Components/Charts/UsersLoans.vue';
import LoansAmountDistribution from '@/Components/Charts/LoansAmountDistribution.vue';
import MonthlyLoanDisbursement from "@/Components/Charts/MonthlyLoanDisbursement.vue";
import LoansByPeriod from "@/Components/Charts/LoansByPeriod.vue";

const props = defineProps({
  usersLoans: Object,
  loansAmountDistributions: Object,
  monthlyLoanDistributions: Object,
  loansByPeriodAnalisys: Object,
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
            <LoansByPeriod :loansByPeriodAnalisys="loansByPeriodAnalisys"/>
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
