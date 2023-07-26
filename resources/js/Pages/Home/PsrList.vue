<script setup lang="ts">
  import PsrCard from '@Components/PsrCard.vue';
  import PsrFooter from '@Components/PsrFooter.vue';
  import PsrHeader from '@Components/PsrHeader.vue';
  import PsrRifaCard from '@Components/PsrRifaCard.vue';
  import PsrSlider from '@Components/PsrSlider.vue';

  defineProps<{
    values: Rifa[];
    slideshows: Slideshow[];
  }>();
</script>

<template>
  <PsrHeader />

  <div>
    <!--
      Slider
     -->
    <PsrSlider :items="slideshows" />
  </div>

  <main class="container max-w-[1024px] min-h-screen">
    <!--
      Content
    -->
    <div class="px-4 py-12 mx-auto xl:px-0">
      <PsrCard>
        <template #heading>//Rifas disponíveis</template>

        <template #default>
          <section>
            <div class="flex flex-wrap -m-4 -mt-0 gap-y-12">
              <article v-for="rifa in values" :key="rifa.slug" class="lg:w-1/4 md:w-1/2 p-4 w-full">
                <PsrRifaCard :rifa="rifa" />
              </article>
            </div>
          </section>
        </template>
      </PsrCard>
    </div>
  </main>

  <PsrFooter />
</template>

<style>
  #app {
    padding: 0;
  }

  /**
  * Header
  */
  nav > a {
    @apply hover:text-[#6366f1] hover:underline underline-offset-4;
  }

  /**
   * Slider
   */
  .slider-items::-webkit-scrollbar {
    display: none;
  }
  .slider-items {
    scrollbar-width: none;
  }
</style>
