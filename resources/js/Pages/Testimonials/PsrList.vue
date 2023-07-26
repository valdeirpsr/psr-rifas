<script setup lang="ts">
  import PsrCard from '@Components/PsrCard.vue';
  import PsrFooter from '@Components/PsrFooter.vue';
  import PsrHeader from '@Components/PsrHeader.vue';

  defineProps<{
    testimonials: Testimonial[];
  }>();
</script>

<template>
  <PsrHeader />

  <main class="container max-w-[1024px] min-h-screen">
    <!--
      Content
    -->
    <div class="px-4 py-12 mx-auto xl:px-0">
      <PsrCard>
        <template #heading>//Depoimentos</template>

        <template #default>
          <section>
            <div class="grid grid-cols-2 gap-4 -m-4 -mt-0">
              <article v-for="testimonial in testimonials" :key="testimonial.id">
                <div class="h-full bg-gray-100 p-8 rounded">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    class="block w-5 h-5 text-gray-400 mb-4"
                    viewBox="0 0 975.036 975.036"
                  >
                    <path
                      d="M925.036 57.197h-304c-27.6 0-50 22.4-50 50v304c0 27.601 22.4 50 50 50h145.5c-1.9 79.601-20.4 143.3-55.4 191.2-27.6 37.8-69.399 69.1-125.3 93.8-25.7 11.3-36.8 41.7-24.8 67.101l36 76c11.6 24.399 40.3 35.1 65.1 24.399 66.2-28.6 122.101-64.8 167.7-108.8 55.601-53.7 93.7-114.3 114.3-181.9 20.601-67.6 30.9-159.8 30.9-276.8v-239c0-27.599-22.401-50-50-50zM106.036 913.497c65.4-28.5 121-64.699 166.9-108.6 56.1-53.7 94.4-114.1 115-181.2 20.6-67.1 30.899-159.6 30.899-277.5v-239c0-27.6-22.399-50-50-50h-304c-27.6 0-50 22.4-50 50v304c0 27.601 22.4 50 50 50h145.5c-1.9 79.601-20.4 143.3-55.4 191.2-27.6 37.8-69.4 69.1-125.3 93.8-25.7 11.3-36.8 41.7-24.8 67.101l35.9 75.8c11.601 24.399 40.501 35.2 65.301 24.399z"
                    />
                  </svg>
                  <p class="leading-relaxed mb-6">{{ testimonial.testimonial }}</p>
                  <a class="inline-flex items-center">
                    <span class="flex-grow flex flex-col pl-4">
                      <span class="title-font font-medium text-gray-900"
                        >— {{ testimonial.order.customer_fullname }}</span
                      >
                    </span>
                  </a>
                </div>
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
