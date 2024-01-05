  <template>
    <FormSection @submitted="createLoan">
      <template #title>
        Create Loan
      </template>

      <template #description>
         Create a new loan
      </template>

      <template #form>
        <div class="col-span-6">
          <InputLabel value="New loan" />
          <div class="flex items-center mt-2">
          </div>
        </div>
        <div class="col-span-6 sm:col-span-4">
          <InputLabel for="user" value="Username" />
          <multiselect
              v-model="form.user"
              :options="users"
              placeholder="Username"
              track-by="id"
              label="name"
          >
          </multiselect>
          <InputError :message="form.errors.user" class="mt-2" />
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
        <div class="col-span-6 sm:col-span-4">
          <InputLabel for="periods" value="Period(in months)" />
          <multiselect
              v-model="form.period"
              :options="periods"
              placeholder="Period"
          >
          </multiselect>
          <InputError :message="form.errors.period" class="mt-2" />
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
  users: Object,
  periods: Object,
});

const form = useForm({
  user: '',
  amount: '',
  period: '',
});

const createLoan = () => {
  form.post(route('loans.store'), {
    errorBag: 'createLoan',
    preserveScroll: true,
  })
}

</script>

<style scoped>

</style>