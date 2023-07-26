<script setup lang="ts">
  import { ref } from 'vue';
  import HeaderMenu from './HeaderMenu.vue';
  import PsrLogo from './PsrLogo.vue';

  const navSideOpen = ref(false);
</script>

<template>
  <header class="bg-white drop-shadow-md">
    <div
      class="container max-w-[1024px] flex flex-wrap p-5 flex-col place-content-center sm:flex-row sm:flex-nowrap sm:gap-4 sm:justify-between"
    >
      <div class="flex flex-wrap items-center justify-between w-full">
        <button type="button" class="appearance-none md:hidden md:invisible" @click="navSideOpen = true">
          <svg
            class="h-7 w-7"
            preserveAspectRatio="xMidYMid meet"
            viewBox="0 0 50 50"
            xmlns="http://www.w3.org/2000/svg"
          >
            <defs>
              <clipPath id="__lottie_element_35"><rect height="50" width="50" x="0" y="0" /></clipPath>
            </defs>
            <g clip-path="url(#__lottie_element_35)">
              <g opacity="1" transform="matrix(1,0,0,1,0,0)" style="display: block">
                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                  <path
                    d=" M0,40 C0,40 50,40 50,40"
                    fill-opacity="0"
                    stroke="rgb(0,0,0)"
                    stroke-linecap="butt"
                    stroke-linejoin="miter"
                    stroke-miterlimit="4"
                    stroke-opacity="1"
                    stroke-width="5"
                  />
                </g>
                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                  <path
                    d=" M50,25 C50,25 0,25 0,25"
                    fill-opacity="0"
                    stroke="rgb(0,0,0)"
                    stroke-linecap="butt"
                    stroke-linejoin="miter"
                    stroke-miterlimit="4"
                    stroke-opacity="1"
                    stroke-width="5"
                  />
                </g>
                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                  <path
                    d=" M50,10 C50,10 0,10 0,10"
                    fill-opacity="0"
                    stroke="rgb(0,0,0)"
                    stroke-linecap="butt"
                    stroke-linejoin="miter"
                    stroke-miterlimit="4"
                    stroke-opacity="1"
                    stroke-width="5"
                  />
                </g>
              </g>
              <g opacity="1" transform="matrix(1,0,0,1,0,0)" style="display: none">
                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                  <path
                    d=" M0,40 C0,40 50,40 50,40"
                    fill-opacity="0"
                    stroke="rgb(0,0,0)"
                    stroke-linecap="butt"
                    stroke-linejoin="miter"
                    stroke-miterlimit="4"
                    stroke-opacity="1"
                    stroke-width="5"
                  />
                </g>
                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                  <path
                    d=" M50,25 C50,25 0,25 0,25"
                    fill-opacity="0"
                    stroke="rgb(0,0,0)"
                    stroke-linecap="butt"
                    stroke-linejoin="miter"
                    stroke-miterlimit="4"
                    stroke-opacity="1"
                    stroke-width="5"
                  />
                </g>
                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                  <path
                    d=" M50,10 C50,10 0,10 0,10 C0,10 50,10 50,10z"
                    fill-opacity="0"
                    stroke="rgb(0,0,0)"
                    stroke-linecap="butt"
                    stroke-linejoin="miter"
                    stroke-miterlimit="4"
                    stroke-opacity="1"
                    stroke-width="5"
                  />
                </g>
              </g>
              <g opacity="1" transform="matrix(1,0,0,1,0,0)" style="display: none">
                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                  <path
                    d=" M50,25 C50,25 0,25 0,25"
                    fill-opacity="0"
                    stroke="rgb(0,0,0)"
                    stroke-linecap="butt"
                    stroke-linejoin="miter"
                    stroke-miterlimit="4"
                    stroke-opacity="1"
                    stroke-width="5"
                  />
                </g>
                <g opacity="1" transform="matrix(1,0,0,1,0,0)">
                  <path
                    d=" M50,25 C50,25 0,25 0,25"
                    fill-opacity="0"
                    stroke="rgb(0,0,0)"
                    stroke-linecap="butt"
                    stroke-linejoin="miter"
                    stroke-miterlimit="4"
                    stroke-opacity="1"
                    stroke-width="5"
                  />
                </g>
              </g>
            </g>
          </svg>
        </button>

        <a :href="route('home')" class="flex font-medium items-center text-gray-900 sm:m-0">
          <PsrLogo />
        </a>
      </div>

      <HeaderMenu v-model:active="navSideOpen" />
    </div>
  </header>
</template>
