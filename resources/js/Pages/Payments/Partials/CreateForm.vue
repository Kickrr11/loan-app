<template>
  <FormSection @submitted="createPayment">
    <template #title>
      Make payment
    </template>

    <template #description>
      Make payment
    </template>

    <template #form>
      <div class="col-span-6">
        <InputLabel value="Make a payment for a loan" />
        <div class="flex items-center mt-2">
        </div>
      </div>
      <div class="col-span-6 sm:col-span-4">
        <InputLabel for="loan code" value="Loan Code" />
        <multiselect
            v-model="form.loan"
            :options="loans"
            placeholder="Loan"
            track-by="id"
            label="loan_code"
        >
        </multiselect>
        <InputError :message="form.errors.loan" class="mt-2" />
      </div>
      <div class="col-span-6 sm:col-span-4">
        <InputLabel for="amount" value="Amount(BGL)" />
        <TextInput
            v-model="form.amount"
            type="number"
            class="block w-full mt-1"
            placeholder="Amount(BGL)"
            min="1"
        />
        <InputError :message="form.errors.amount" class="mt-2" />
      </div>
    </template>

    <template #actions>
      <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
        Create
      </PrimaryButton>
    </template>
  </FormSection>

</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Multiselect from "@suadelabs/vue3-multiselect";
import {computed} from "vue";

const props = defineProps({
  loans: Object,
});

const form = useForm({
  loan: '',
  amount: '',
});


const createPayment = () => {
  form.post(route('payments.store'), {
    errorBag: 'createPayment',
    preserveScroll: true,
  })
}

</script>

<style scoped>

</style>