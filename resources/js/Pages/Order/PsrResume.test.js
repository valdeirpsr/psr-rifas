import { describe, expect, it, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import PsrShow from './PsrResume.vue';
import PsrBadge from '@Components/PsrBadge.vue';
import { ZiggyVue } from '../../../../vendor/tightenco/ziggy/dist/vue.m';
import { Ziggy } from '../../ziggy';
import { ref } from 'vue';

vi.stubGlobal('route', vi.fn());

vi.mock('@inertiajs/vue3', (inertiaOriginal) => ({
  ...inertiaOriginal,
  useForm: function (formData) {
    return {
      ...formData,
      processing: ref(false),
      post: function () {
        this.processing.value = true;
      },
    };
  },
}));

const getValidData = () => {
  const expire_at = new Date(Date.now() + 3600).toLocaleString('af');

  return {
    order: {
      id: 535,
      customer_fullname: 'Valdeir Psr',
      customer_email: 'test@test.com',
      customer_telephone: '11111111111',
      numbers_reserved: ['2290', '29400', '49869', '58339', '84963'],
      status: 'reserved',
      expire_at: expire_at,
      transaction_amount: 2.7,
    },
    rifa: {
      id: 1,
      title: 'First Draw',
      price: 0.54,
    },
  };
};

const selectorButtonPay = '[data-testid="button-payment"]';

describe('Testa renderização do layout', () => {
  const selectorNumbers = '[data-testid="numbers"]';
  const selectorButtonToggleNumbers = '[data-testid="button-toggle-numbers"]';

  it('Testa se os números são exibidos ao clicar em "Ver números"', async () => {
    const wrapper = mount(PsrShow, {
      props: getValidData(),
    });

    expect(wrapper.find(selectorNumbers).exists()).toBeFalsy();
    await wrapper.get(selectorButtonToggleNumbers).trigger('click');
    expect(wrapper.find(selectorNumbers).exists()).toBeTruthy();
  });

  it('Verifica se os números são renderizados corretamente', async () => {
    const wrapper = mount(PsrShow, {
      props: getValidData(),
    });

    await wrapper.get(selectorButtonToggleNumbers).trigger('click');

    const divNumbers = wrapper.get(selectorNumbers).findAllComponents(PsrBadge);
    expect(divNumbers.length).toEqual(5);

    expect(divNumbers[0].element.textContent).toEqual('2290');
    expect(divNumbers[1].element.textContent).toEqual('29400');
  });

  it('A expiração, o cronômetro e o botão de pagar não devem aparecer quando o pedido estiver pago', () => {
    const wrapper = mount(PsrShow, {
      props: {
        order: {
          id: 535,
          customer_fullname: 'Valdeir Psr',
          customer_email: 'test@test.com',
          customer_telephone: '11111111111',
          numbers_reserved: ['2290', '29400', '49869', '58339', '84963'],
          status: 'paid',
          expire_at: '2023-07-04 23:59:59',
          transaction_amount: 2.7,
        },
        rifa: { id: 1, title: 'First Draw', price: 0.54 },
      },
    });

    expect(wrapper.find('[data-testid="expire"]').exists()).toBeFalsy();
    expect(wrapper.find('[data-testid="countdown"]').exists()).toBeFalsy();
    expect(wrapper.find(selectorButtonPay).exists()).toBeFalsy();
  });
});

describe('Teste de interação', () => {
  it('O botão Pagar deverá ser bloqueado após clique', async () => {
    const wrapper = mount(PsrShow, {
      props: getValidData(),
      global: {
        plugins: [[ZiggyVue, Ziggy]],
      },
    });

    await wrapper.get(selectorButtonPay).trigger('click');
    expect(wrapper.get(selectorButtonPay).attributes('disabled')).not.toBeUndefined();
    expect(wrapper.get(selectorButtonPay).text()).toEqual('Aguarde');
  });
});
