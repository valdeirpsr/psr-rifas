<script setup lang="ts">
  import { computed, ref } from 'vue';
  import axios from 'axios';
  import { vMaska } from 'maska/vue';
  import { useTelephone } from '../Composables/Validators';
  import { IconSvgWhatsApp } from '@Assets/icons';
  import PsrButton from './PsrButton.vue';
  import PsrDialog from './PsrDialog.vue';
  import ListOrders from './ListOrders.vue';
  import PsrLoading from './PsrLoading.vue';

  const props = defineProps<{
    rifaSlug: string;
  }>();

  const opened = ref(false);
  const telephone = ref('');
  const orders = ref<OrderWithPayment[]>([]);
  const isLoading = ref(false);

  const isValidTelephone = computed(() => useTelephone(telephone.value));

  /**
   * Realiza busca de dados na API
   */
  async function searchOrders() {
    isLoading.value = true;
    const telephoneRaw = telephone.value.replace(/\D/g, '');

    const { data: response } = await axios.get<{ data: OrderWithPayment[] }>(
      route('rifas.show.orders', [props.rifaSlug, telephoneRaw])
    );

    orders.value = response.data;
    isLoading.value = false;
  }

  function onDialogClosed() {
    orders.value = [];
    opened.value = false;
    telephone.value = '';
  }
</script>

<template>
  <div>
    <PsrButton type="link" @click="opened = true">🔍 Meus Bilhetes</PsrButton>

    <Teleport v-if="opened" to="body">
      <PsrDialog :button-cancel="false" :button-confirm="false" @dismiss="onDialogClosed">
        <template #heading>
          <span v-if="orders.length === 0">Informe o número do seu telefone</span>
          <span v-else>Lista de bilhetes</span>
        </template>

        <template #default>
          <Transition name="fade">
            <div v-if="orders.length === 0 && !isLoading">
              <p>Informe o número do celular cadastrado</p>

              <div class="rounded-md shadow-sm mt-4">
                <div class="flex left-0 top-0 bottom-0 items-center pl-2 relative">
                  <IconSvgWhatsApp class="absolute ml-1 text-gray-400" />

                  <input
                    v-model="telephone"
                    v-maska
                    type="tel"
                    class="block rounded-md border py-1 pl-10 text-[#333] shadow ring-inset ring-[#d1d5db] text-sm leading-6 h-12 w-full"
                    placeholder="(71) 9 0000-0000"
                    data-testid="input-telephone"
                    data-maska="(##) #####-####"
                  />
                </div>
              </div>

              <div class="mt-4">
                <PsrButton
                  v-show="isValidTelephone"
                  class="w-full"
                  data-testid="button-search-orders"
                  :disabled="!isValidTelephone"
                  @click="searchOrders"
                >
                  Buscar
                </PsrButton>
              </div>
            </div>

            <PsrLoading v-else-if="orders.length === 0 && isLoading" />

            <div v-else class="mt-4 space-y-4">
              <ListOrders :orders="orders" />
            </div>
          </Transition>
        </template>
      </PsrDialog>
    </Teleport>
  </div>
</template>
