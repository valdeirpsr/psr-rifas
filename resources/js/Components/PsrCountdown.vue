<script setup lang="ts">
  import { computed } from 'vue';
  import VueCountdown from '@chenfengyuan/vue-countdown';

  const props = defineProps<{
    time: Date|number|string
  }>();

  const time = computed((): number => {
    if (props.time instanceof Date) {
      return props.time.getTime() - Date.now();
    }

    if (typeof props.time === 'string') {
      return new Date(props.time).getTime() - Date.now();
    }

    return props.time;
  });
</script>

<template>
  <VueCountdown v-slot="{ totalHours, minutes, seconds }" :time="time">
    <slot>{{ totalHours }} horas, {{ minutes }} minutos e {{ seconds }} segundos</slot>
  </VueCountdown>
</template>
