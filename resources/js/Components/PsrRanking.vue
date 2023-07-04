<script setup lang="ts">
  import { computed, readonly } from 'vue';
  import PsrCard from './PsrCard.vue';

  const props = defineProps<{
    users: Ranking[];
  }>();

  const ranking = computed(() =>
    Array.from(props.users)
      .sort((prev, cur) => (prev.total_numbers < cur.total_numbers ? 1 : -1))
      .slice(0, 3)
  );

  const medals = readonly(['🥇', '🥈', '🥉']);
</script>

<template>
  <PsrCard>
    <template #heading> Ranking dos compradores </template>

    <div class="flex flex-wrap justify-around gap-4 mx-auto sm:max-w-xl text-center">
      <div
        v-for="(user, index) in ranking"
        :key="`ranking-${user.total_numbers}`"
        :class="index === 0 ? 'flex-initial w-full sm:flex-auto sm:w-auto' : 'flex-none sm:flex-auto'"
        data-test="ranking-user"
      >
        <span class="text-[32px]">{{ medals.at(index) }}</span>
        <p class="text-sm mt-3">
          {{ user.customer_fullname }}<br />
          <b>{{ user.total_numbers }}</b> bilhetes
        </p>
      </div>
    </div>
  </PsrCard>
</template>
