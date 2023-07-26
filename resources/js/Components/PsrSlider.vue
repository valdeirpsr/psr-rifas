<script setup lang="ts">
  import { computed, ref, watch } from 'vue';

  const props = defineProps<{
    items: Slideshow[];
  }>();

  const sliderIndex = ref(0);
  const sliderItemsEl = ref<HTMLDivElement>();

  function sliderPrev() {
    if (!sliderItemsEl.value) return;
    const newIndex = sliderIndex.value - 1;

    if (newIndex < 0) return;
    sliderIndex.value = newIndex;
  }

  function sliderNext() {
    if (!sliderItemsEl.value) return;
    const newIndex = sliderIndex.value + 1;

    if (sliderItemsEl.value.childElementCount < newIndex) return;
    sliderIndex.value++;
  }

  const isInitialSlider = computed(() => sliderIndex.value === 0);
  const isFinalSlider = computed(() => sliderIndex.value === props.items.length - 1);

  watch(sliderIndex, () => {
    if (!sliderItemsEl.value) return;
    sliderItemsEl.value.scrollLeft = sliderIndex.value * sliderItemsEl.value?.clientWidth;
  });
</script>

<template>
  <section class="slider group md:relative">
    <div ref="sliderItemsEl" class="slider-items flex overflow-x-auto snap-always snap-mandatory snap-x scroll-smooth">
      <img
        v-for="slideshow in items"
        :key="slideshow.image"
        class="flex-auto flex-shrink-0 max-h-[570px] object-cover snap-start w-full"
        :src="slideshow.image"
        :alt="slideshow.alt"
      />
    </div>

    <div
      class="slider-controls flex absolute top-1/2 w-full justify-between px-4 opacity-0 translate-y-4 transition group-hover:translate-y-0 group-hover:opacity-100"
    >
      <button
        type="button"
        class="bg-gray-50 block h-10 rounded-full select-none w-10"
        :class="{ invisible: isInitialSlider }"
        @click="sliderPrev"
      >
        &lt;
      </button>

      <button
        type="button"
        class="bg-gray-50 block h-10 rounded-full select-none w-10"
        :class="{ invisible: isFinalSlider }"
        @click="sliderNext"
      >
        &gt;
      </button>
    </div>
  </section>
</template>
