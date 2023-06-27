<script setup lang="ts">
  import { computed, readonly } from 'vue';
  import { RankingType } from '../Types/Ranking';
  import PsrCard from './PsrCard.vue';

  const props = defineProps<{
    users: RankingType[];
  }>();

  const ranking = computed(() =>
    Array.from(props.users)
      .sort((prev, cur) => (prev.total < cur.total ? 1 : -1))
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
        :key="`ranking-${user.total}`"
        :class="index === 0 ? 'flex-initial w-full sm:flex-auto sm:w-auto' : 'flex-none sm:flex-auto'"
        data-test="ranking-user"
      >
        <span class="text-[32px]">{{ medals.at(index) }}</span>
        <p class="text-sm mt-3">
          {{ user.name }}<br />
          <b>{{ user.total }}</b> bilhetes
        </p>
      </div>
    </div>
  </PsrCard>
</template>
