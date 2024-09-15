<script setup lang="ts">
  import { ref } from 'vue';
  import { onClickOutside } from '@vueuse/core';
  import PsrButton from './PsrButton.vue';

  withDefaults(
    defineProps<{
      buttonCancel?: boolean;
      buttonConfirm?: boolean;
      buttonConfirmDisabled?: boolean;
    }>(),
    {
      buttonCancel: true,
      buttonConfirm: true,
      buttonConfirmDisabled: false,
    }
  );

  const emits = defineEmits<{
    (event: 'dismiss'): void;
    (event: 'confirm'): void;
  }>();

  const modalContent = ref();

  onClickOutside(modalContent, () => {
    emits('dismiss');
  });
</script>

<template>
  <div
    class="fixed h-screen left-0 top-0 w-screen z-10"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-heading"
    aria-describedby="modal-content"
  >
    <div class="fixed inset-0 bg-gray-600 bg-opacity-75 transition-opacity" data-testid="modal-overlay" />

    <div ref="modalContent" class="top-0 overflow-y-auto">
      <div class="flex min-h-screen justify-center p-4 text-center items-center sm:p-0">
        <div
          class="relative overflow-hidden rounded-lg bg-white text-left shadow-xl sm:my-8 sm:w-full sm:max-w-lg dialog-content"
        >
          <div class="p-4 sm:p-10 text-center overflow-y-auto">
            <h3 id="modal-heading" class="mb-5 text-2xl font-bold text-gray-800">
              <slot name="heading" />
            </h3>
            <div id="modal-content" class="text-gray-500"><slot /></div>

            <slot v-if="buttonCancel || buttonConfirm" name="footer">
              <div class="mt-6 flex justify-end gap-x-4">
                <PsrButton v-if="buttonCancel" type="link" data-testid="button-cancel" @click="$emit('dismiss')">
                  Cancelar
                </PsrButton>

                <PsrButton
                  v-if="buttonConfirm"
                  data-testid="button-confirm"
                  :disabled="buttonConfirmDisabled"
                  @click="$emit('confirm')"
                >
                  Confirmar
                </PsrButton>
              </div>
            </slot>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
  .dialog-content {
    max-height: calc(100vh - 4rem);
    overflow: auto;
  }
</style>
