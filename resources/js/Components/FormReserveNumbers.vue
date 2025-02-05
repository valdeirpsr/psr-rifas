<script setup lang="ts">
  import { vMaska } from 'maska/vue';
  import { useForm } from '@inertiajs/vue3';
  import { IconSvgEmail, IconSvgPeople, IconSvgTelephone } from '@Assets/icons';
  import PsrDialog from './PsrDialog.vue';

  defineEmits<{
    (event: 'confirm', value: Partial<FormReserveNumbers>): Promise<void>;
    (event: 'dismiss'): void;
  }>();

  const props = defineProps<{
    rifa: Rifa['id'];
    quantity: FormReserveNumbers['quantity'];
  }>();

  const form = useForm<FormReserveNumbers>({
    confirmTelephone: '',
    email: '',
    fullname: '',
    quantity: props.quantity,
    rifa: props.rifa,
    telephone: '',
    terms: false,
  });

  function submitForm() {
    form.post(route('orders.store'));
  }
</script>

<template>
  <PsrDialog @confirm="submitForm" @dismiss="$emit('dismiss')">
    <template #heading>Reservar Bilhetes</template>

    <template #default>
      <p>Preencha as informações para prosseguir</p>

      <div class="text-start" :class="{ error: form.errors.fullname }">
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
            :aria-invalid="!!form.errors.fullname"
          />
        </div>

        <span v-if="form.errors.fullname" id="error-fullname" class="text-xs">Informe seu nome completo</span>
      </div>

      <div class="text-start" :class="{ error: form.errors.email }">
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
            :aria-invalid="!!form.errors.email"
          />
        </div>

        <span v-if="form.errors.email" id="error-email" class="text-xs">Informe um e-mail válido</span>
      </div>

      <div class="text-start" :class="{ error: form.errors.telephone }">
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
            :aria-invalid="!!form.errors.telephone"
          />
        </div>

        <span v-if="form.errors.telephone" id="error-telephone" class="text-xs">Informe seu telefone</span>
      </div>

      <div class="text-start" :class="{ error: form.errors.confirmTelephone }">
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
            :aria-invalid="!!form.errors.confirmTelephone"
          />
        </div>

        <span v-if="form.errors.confirmTelephone" id="error-confirm-telephone" class="text-xs">
          Confirme seu telefone
        </span>
      </div>

      <div class="relative flex gap-x-3 text-start mt-4" :class="{ error: form.errors.terms }">
        <div class="flex h-6 items-center">
          <input
            id="terms"
            v-model="form.terms"
            type="checkbox"
            data-testid="input-terms"
            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
            aria-errormessage="error-terms"
            :aria-invalid="!!form.errors.terms"
          />
        </div>
        <div class="text-sm leading-6">
          <label class="text-gray-500" for="terms">
            Li e concordo com os
            <!-- prettier-ignore -->
            <a
              class="underline-blue-400 underline-4 underline"
              :href="route('terms')"
              target="_blank"
            >Termos e Condições</a>
            e estou ciente de que essa reserva me vincula apenas à esta campanha
          </label>

          <p v-if="form.errors.terms" id="error-terms" class="text-xs">
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
