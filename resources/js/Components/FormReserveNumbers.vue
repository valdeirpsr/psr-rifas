<script setup lang="ts">
  import { reactive } from 'vue';
  import { vMaska } from 'maska';
  import { useVuelidate } from '@vuelidate/core';
  import { email, required, sameAs } from '@vuelidate/validators';
  import { IconSvgEmail, IconSvgPeople, IconSvgTelephone } from '@Assets/icons';
  import { useFullname, useSameAs } from '../Composables/Validators';
  import PsrDialog from './PsrDialog.vue';

  const emits = defineEmits<{
    (event: 'confirm', value: FormReserveNumbers): void;
    (event: 'dismiss'): void;
  }>();

  const form = reactive<FormReserveNumbers>({
    fullname: '',
    email: '',
    telephone: '',
    confirmTelephone: '',
    terms: false,
  });

  const v$ = useVuelidate(
    {
      fullname: { required, regex: useFullname() },
      email: { email, required },
      telephone: { required },
      confirmTelephone: {
        required,
        sameAsTelephone: useSameAs(form, 'telephone', 'Telefone'),
      },
      terms: {
        required,
        sameAsRawValue: sameAs(true),
      },
    },
    form
  );

  async function isValid() {
    if (!(await v$.value.$validate())) return;
    emits('confirm', form);
  }
</script>

<template>
  <PsrDialog @confirm="isValid" @dismiss="$emit('dismiss')">
    <template #heading>Reservar Bilhetes</template>

    <template #default>
      <p>Preencha as informações para prosseguir</p>

      <div class="text-start" :class="{ error: v$.fullname.$error }">
        <div class="rounded-md shadow-sm mt-4 relative">
          <div class="flex left-0 absolute top-0 bottom-0 items-center pl-2">
            <IconSvgPeople class="text-gray-400" height="24" width="24" />
          </div>

          <input
            v-model="form.fullname"
            type="text"
            placeholder="Nome completo"
            data-testid="input-full-name"
            autocomplete="name"
            aria-label="Informe seu nome completo"
            required
            aria-errormessage="error-fullname"
            :aria-invalid="v$.fullname.$error"
          />
        </div>

        <span v-if="v$.fullname.$error" id="error-fullname" class="text-xs">Informe seu nome completo</span>
      </div>

      <div class="text-start" :class="{ error: v$.email.$error }">
        <div class="rounded-md shadow-sm mt-4 relative">
          <div class="flex left-0 absolute top-0 bottom-0 items-center pl-2">
            <IconSvgEmail class="text-gray-400" height="24" width="24" />
          </div>

          <input
            v-model="form.email"
            type="email"
            placeholder="E-mail"
            data-testid="input-email"
            autocomplete="email"
            aria-label="Informe seu e-mail de contato"
            aria-errormessage="error-email"
            required
            :aria-invalid="v$.email.$error"
          />
        </div>

        <span v-if="v$.email.$error" id="error-email" class="text-xs">Informe um e-mail válido</span>
      </div>

      <div class="text-start" :class="{ error: v$.telephone.$error }">
        <div class="rounded-md shadow-sm mt-4 relative">
          <div class="flex left-0 absolute top-0 bottom-0 items-center pl-2">
            <IconSvgTelephone class="text-gray-400" height="24" width="24" />
          </div>

          <input
            v-model="form.telephone"
            v-maska
            type="tel"
            placeholder="Informe seu telefone"
            data-testid="input-telephone"
            autocomplete="tel"
            aria-label="Informe seu telefone para contato"
            aria-errormessage="error-telephone"
            data-maska="(##) #####-####"
            required
            :aria-invalid="v$.telephone.$error"
          />
        </div>

        <span v-if="v$.telephone.$error" id="error-telephone" class="text-xs">Informe seu telefone</span>
      </div>

      <div class="text-start" :class="{ error: v$.confirmTelephone.$error }">
        <div class="rounded-md shadow-sm mt-4 relative">
          <div class="flex left-0 absolute top-0 bottom-0 items-center pl-2">
            <IconSvgTelephone class="text-gray-400" height="24" width="24" />
          </div>

          <input
            v-model="form.confirmTelephone"
            v-maska
            type="tel"
            placeholder="Confirme seu telefone"
            autocomplete="off"
            data-testid="input-confirm-telephone"
            aria-label="Confirme seu telefone para contato"
            aria-errormessage="error-confirm-telephone"
            data-maska="(##) #####-####"
            required
            :aria-invalid="v$.confirmTelephone.$error"
          />
        </div>

        <span v-if="v$.confirmTelephone.$error" id="error-confirm-telephone" class="text-xs">
          Confirme seu telefone
        </span>
      </div>

      <div class="relative flex gap-x-3 text-start mt-4" :class="{ error: v$.terms.$error }">
        <div class="flex h-6 items-center">
          <input
            id="terms"
            v-model="form.terms"
            type="checkbox"
            data-testid="input-terms"
            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
            aria-errormessage="error-terms"
            :aria-invalid="v$.terms.$error"
          />
        </div>
        <div class="text-sm leading-6">
          <label class="text-gray-500" for="terms">
            Li e concordo com os <a href="#" class="underline-blue-400 underline-4 underline">Termos e Condições</a> e
            estou ciente de que essa reserva me vincula apenas à esta campanha
          </label>

          <p v-if="v$.terms.$error" id="error-terms" class="text-xs">
            É necessário concordar com os termos para prosseguir
          </p>
        </div>
      </div>
    </template>
  </PsrDialog>
</template>

<style scoped>
  input:not([type='checkbox']) {
    @apply block rounded-md border py-1 pl-10 text-[#333] shadow ring-inset ring-[#d1d5db] text-sm leading-6 h-12 w-full;
  }

  div.error input,
  div.error input::placeholder,
  div.error svg,
  div.error span,
  div.error p {
    border-color: #a22e2e;
    color: #a22e2e;
  }
</style>
