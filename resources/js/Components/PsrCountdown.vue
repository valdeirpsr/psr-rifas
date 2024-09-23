<script setup lang="ts">
  import { computed } from 'vue';
  import VueCountdown from '@chenfengyuan/vue-countdown';

  defineEmits<{
    (event: 'end'): void;
  }>();

  const props = defineProps<{
    time: Date | number | string | null;
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
  <VueCountdown v-if="time !== null" v-slot="{ totalHours, minutes, seconds }" :time="time" @end="$emit('end')">
    <slot>{{ totalHours }} horas, {{ minutes }} minutos e {{ seconds }} segundos</slot>
  </VueCountdown>
</template>
