<template>
    <AppLayout title="Loans">
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Loans
        </h2>
        <div class="container-fluid">
          <table class="table-auto w-full">
            <thead>
            <tr class="bg-gray-100">
              <th class="py-2 min-w-20">Borrower name</th>
              <th class="py-2">Remaining Amount (BGL)</th>
              <th class="py-2">Period(in months)</th>
              <th class="py-2">Monthly fee (BGL)</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="loans && loans.data" v-for="loan in loans.data" :key="loan.id">
              <td class="border px-4 py-2"><div class="flex space-x-2">
                <div>
                  {{ loan.borrower.name }}
                </div>
              </div>
              </td>
              <td class="border px-4 py-2 text-sm">
                {{ loan.remaining_amount }}
              </td>
              <td class="border px-4 py-2 text-sm">
                {{ loan.term_months }}
              </td>
              <td class="border px-4 py-2 text-sm">
                {{ loan.monthly_fee }}
              </td>
            </tr>
            </tbody>
          </table>
          <hr>
          <Pagination :preserveState="true" :links="loans.links"/>
        </div>
      </template>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Pagination from '@/Components/Shared/Pagination.vue';

const props = defineProps({
  loans: Object,
})
</script>

<style scoped>

</style>