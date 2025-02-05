import { mount } from '@vue/test-utils';
import { describe, expect, it, vi, beforeEach } from 'vitest';
import axios from 'axios';
import ButtonListOrders from './ButtonListOrders.vue';
import PsrDialog from './PsrDialog.vue';
import PsrLoading from './PsrLoading.vue';

vi.mock('axios');

beforeEach(() => {
  axios.get.mockReset();
});

vi.stubGlobal('route', vi.fn().mockReturnValue('/rifas/rifa-slug/orders/71900000000'));

const selectorButtonSearchOrders = '[data-testid="button-search-orders"]';
const selectorInputpTelephone = '[data-testid="input-telephone"]';

describe('Teste de renderização do componente', () => {
  it('O botão de pesquisar deve aparecer quando o usuário informar o número de telefone', async () => {
    const wrapper = mount(ButtonListOrders, {
      props: {
        rifaSlug: 'rifa-slug',
      },
    });

    await wrapper.get('button').trigger('click');
    const dialogComponent = wrapper.getComponent(PsrDialog);

    expect(dialogComponent.find(selectorButtonSearchOrders).isVisible()).toBeFalsy();
    await dialogComponent.get(selectorInputpTelephone).setValue('(71) 9 0000-0000');
    expect(dialogComponent.find(selectorButtonSearchOrders).isVisible()).toBeTruthy();
  });

  it('Ao clicar em Buscar, o componente de loading deverá ser exibido', async () => {
    const wrapper = mount(ButtonListOrders, {
      props: {
        rifaSlug: 'rifa-slug',
      },
    });

    /** Simula delay na requisição */
    axios.get.mockImplementation(() => {
      return new Promise((resolve) => {
        setTimeout(() => {
          resolve({ data: { data: [] } });
        }, 1000);
      });
    });

    await wrapper.get('button').trigger('click');
    const dialogComponent = wrapper.getComponent(PsrDialog);

    expect(wrapper.findComponent(PsrLoading).exists()).toBeFalsy();
    await dialogComponent.get(selectorInputpTelephone).setValue('(71) 9 0000-0000');
    await dialogComponent.get(selectorButtonSearchOrders).trigger('click');
    expect(wrapper.findComponent(PsrLoading).exists()).toBeTruthy();
  });

  it('Ao clicar em buscar, o componente deverá solicitar os dados para API', async () => {
    const wrapper = mount(ButtonListOrders, {
      props: {
        rifaSlug: 'rifa-slug',
      },
    });

    axios.get.mockResolvedValue({
      data: { data: [] },
    });

    await wrapper.get('button').trigger('click');
    const dialogComponent = wrapper.getComponent(PsrDialog);

    await dialogComponent.get(selectorInputpTelephone).setValue('(71) 9 0000-0000');
    await dialogComponent.get(selectorButtonSearchOrders).trigger('click');

    expect(axios.get).toHaveBeenCalledWith('/rifas/rifa-slug/orders/71900000000');
  });
});
