<script setup lang="ts">
  import PsrButton from '@Components/PsrButton.vue';
  import PsrCard from '@Components/PsrCard.vue';
  import PsrFooter from '@Components/PsrFooter.vue';
  import PsrHeader from '@Components/PsrHeader.vue';
  import { useForm } from '@inertiajs/vue3';

  withDefaults(
    defineProps<{
      success: boolean;
    }>(),
    {
      success: false,
    }
  );

  const form = useForm({
    name: '',
    email: '',
    message: '',
  });

  function submit() {
    form.post(route('contact.post'), {
      onSuccess: () => form.reset(),
    });
  }
</script>

<template>
  <PsrHeader />

  <main class="container max-w-[1024px] min-h-screen">
    <!--
      Content
    -->
    <div class="px-4 py-12 mx-auto xl:px-0">
      <PsrCard>
        <template #default>
          <!-- ------------------------------ -->
          <!-- Author: https://tailblocks.cc/ -->
          <!-- ------------------------------ -->
          <section class="text-gray-600 body-font relative">
            <div class="container px-5 py-12 mx-auto">
              <div class="flex flex-col text-center w-full mb-12">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">Contato</h1>
                <p class="lg:w-2/3 mx-auto leading-relaxed text-base">
                  Lorem ipsum dolor sit amet consectetur, adipisicing elit. Nostrum culpa perferendis ad ut incidunt
                  delectus. Possimus doloribus quidem dolorum tenetur molestias, ipsam non reiciendis ducimus nesciunt
                  eius vel quam ad!.
                </p>
              </div>

              <div
                v-if="form.hasErrors"
                class="text-center mb-12 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded lg:w-2/3 mx-auto"
                role="alert"
              >
                Erro ao validar o formuário. Preencha as informações corretamente.
              </div>

              <div
                v-if="success"
                class="text-center mb-12 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded lg:w-2/3 mx-auto"
                role="alert"
              >
                <strong class="font-bold">Sucesso! </strong>
                <span class="block sm:inline">Formulário enviado com sucesso.</span>
              </div>

              <div class="lg:w-1/2 md:w-2/3 mx-auto">
                <form method="post" class="flex flex-wrap -m-2" @submit.prevent="submit">
                  <div class="p-2 w-1/2" :class="{ error: form.errors.name }">
                    <div class="relative">
                      <label for="name" class="leading-7 text-sm text-gray-600">Nome</label>
                      <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                        :aria-invalid="!!form.errors.name"
                        :disabled="form.processing"
                      />

                      <span v-if="form.errors.name" id="error-name" class="text-xs" role="status">
                        Informe seu nome
                      </span>
                    </div>
                  </div>
                  <div class="p-2 w-1/2" :class="{ error: form.errors.email }">
                    <div class="relative">
                      <label for="email" class="leading-7 text-sm text-gray-600">E-mail</label>
                      <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        required
                        :aria-invalid="!!form.errors.email"
                        :disabled="form.processing"
                      />

                      <span v-if="form.errors.name" id="error-email" class="text-xs" role="status">
                        Informe seu email corretamente
                      </span>
                    </div>
                  </div>
                  <div class="p-2 w-full" :class="{ error: form.errors.message }">
                    <div class="relative">
                      <label for="message" class="leading-7 text-sm text-gray-600">Mensagem</label>
                      <textarea
                        id="message"
                        v-model="form.message"
                        required
                        :disabled="form.processing"
                        :aria-invalid="!!form.errors.message"
                      />
                    </div>

                    <span v-if="form.errors.message" id="error-name" class="text-xs" role="status">
                      Escreva sua mensagem
                    </span>
                  </div>
                  <div class="p-2 text-center w-full">
                    <PsrButton :disabled="form.processing">
                      <span v-if="!form.processing" class="text-white">Enviar</span>
                      <span v-else class="text-white">Aguarde...</span>
                    </PsrButton>
                  </div>
                  <div class="p-2 w-full pt-8 mt-8 border-t border-gray-200 text-center">
                    <a class="text-indigo-500">example@email.com</a>
                    <p class="leading-normal my-5">
                      49 Smith St.
                      <br />Saint Cloud, MN 56301
                    </p>
                  </div>
                </form>
              </div>
            </div>
          </section>
        </template>
      </PsrCard>
    </div>
  </main>

  <PsrFooter />
</template>

<style>
  input,
  textarea {
    @apply w-full
  bg-gray-100
  bg-opacity-50
  rounded
  border
  border-gray-300
  focus:border-indigo-500
  focus:bg-white
  focus:ring-2
  focus:ring-indigo-200
  text-base
  outline-none
  text-gray-700
  py-1
  px-3
  leading-8
  transition-colors
  duration-200
  ease-in-out;
  }

  textarea {
    @apply h-32 resize-none leading-6 transition-colors duration-200 ease-in-out;
  }

  div.error input,
  div.error input::placeholder,
  div.error svg,
  div.error span,
  div.error p,
  div.error textarea {
    border-color: #a22e2e;
    color: #a22e2e;
  }
</style>
