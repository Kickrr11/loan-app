<template>
  <div v-if="links.length > 3" class="flex justify-center">
    <div class="flex">
      <template v-for="(link, p) in displayedLinks" :key="p">
        <template v-if="link.url !== null">
          <NavLink
              class="mx-1 px-3 py-2 text-sm leading-5 border rounded hover:bg-primary hover:text-white focus:bg-primary focus:text-white"
              :class="{ 'bg-primary text-white': link.active }"
              :href="link.url"
              v-html="link.label"
              :preserveState="preserveState"
              :preserveScroll="preserveState"
          />
        </template>
        <template v-else>
          <span class="mx-1 px-3 py-2 text-sm leading-5 border rounded">...</span>
        </template>
      </template>
    </div>
  </div>
</template>

<script setup>
import NavLink from '@/Components/NavLink.vue';
import { ref, computed } from 'vue';

const props = defineProps({
  links: Array,
  preserveState: Boolean,
});
const links = ref(props.links);

const displayedLinks = computed(() => {
  const currentPage = links.value.find(link => link.active)?.url;
  const currentIdx = links.value.findIndex(link => link.url === currentPage);
  const totalLinks = links.value.length;

  if (totalLinks <= 5) {
    return links.value;
  }

  const prevLinks = links.value.slice(0, currentIdx + 1);
  const nextLinks = links.value.slice(currentIdx);

  const result = [];

  if (currentIdx >= 3) {
    result.push(...prevLinks.slice(-3));
    result.push({ url: null, label: '...' });
  } else {
    result.push(...links.value.slice(0, 5));
  }

  if (currentIdx < totalLinks - 3) {
    result.push({ url: nextLinks[3].url, label: 'Next &raquo;' });
  }

  if (currentIdx >= 3) {
    result.unshift({ url: prevLinks[0].url, label: '&laquo; Previous' });
  }

  return result;
});

</script>

<style scoped>

</style>