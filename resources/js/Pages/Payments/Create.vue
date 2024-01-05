<template>
  <AppLayout title="Create Auction">
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
      <CreateForm :loans="loans"/>
    </div>
  </AppLayout>

</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CreateForm from "@/Pages/Payments/Partials/CreateForm.vue";

const props = defineProps({
  loans: Object,
});

</script>

<style scoped>
</style>
<style src="@suadelabs/vue3-multiselect/dist/vue3-multiselect.css"></style>