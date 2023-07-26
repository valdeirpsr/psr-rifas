import { reactive } from 'vue';
import { describe, expect, it, vi } from 'vitest';
import { mount } from '@vue/test-utils';
import { ZiggyVue } from '../../../vendor/tightenco/ziggy/dist/vue.m';
import { Ziggy } from '../ziggy';
import FormReserveNumbers from './FormReserveNumbers.vue';
import { beforeEach } from 'vitest';

vi.stubGlobal('route', vi.fn().mockReturnValue('/orders'));

vi.mock('@inertiajs/vue3', (inertiaOriginal) => ({
  ...inertiaOriginal,
  useForm: (formData) => {
    const form = reactive({
      ...formData,
      hasErrors: false,
      errors: {
        fullname: false,
        email: false,
        telephone: false,
        confirmTelephone: false,
        terms: false,
      },
      post: vi.fn(() => {
        form.errors = {
          fullname: !form.fullname.match(/[a-záàâãéèêíïóôõöúçñü]{3,}\s[a-záàâãéèêíïóôõöúçñü\s]{3,}/gi),
          email: !form.email.match(/^((?!\.)[\w\-_.]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$/),
          telephone: form.telephone.replace(/\D/g, '').match('/[0-9]{11,12}/'),
          confirmTelephone: form.confirmTelephone.replace(/\D/g, '') !== form.telephone.replace(/\D/g, ''),
          terms: !form.terms,
        };
      }),
    });

    return form;
  },
}));

describe('Testa interação do formulário', () => {
  let wrapper;

  beforeEach(() => {
    wrapper = mount(FormReserveNumbers, {
      props: {
        rifa: 1,
        quantity: 1,
      },
      global: {
        plugins: [[ZiggyVue, Ziggy]],
      },
    });
  });

  it('O nome e o sobrenome devem ter pelo menos 3 caracteres', async () => {
    expect(wrapper.find('#error-fullname').exists()).toBeFalsy();

    await wrapper.get('[data-testid="input-full-name"]').setValue('Oi Hi');
    await wrapper.get('[data-testid="button-confirm"]').trigger('click');

    expect(wrapper.get('#error-fullname').isVisible()).toBeTruthy();
  });

  it('O email deve ser válido', async () => {
    expect(wrapper.find('#error-email').exists()).toBeFalsy();

    await wrapper.get('[data-testid="input-email"]').setValue('test');
    await wrapper.get('[data-testid="button-confirm"]').trigger('click');

    expect(wrapper.get('#error-email').isVisible()).toBeTruthy();
  });

  it('A confirmação do telefone deve ser igual ao telefone', async () => {
    expect(wrapper.find('#error-confirm-telephone').exists()).toBeFalsy();

    await wrapper.get('[data-testid="input-telephone"]').setValue('(00) 91234-5678');
    await wrapper.get('[data-testid="input-confirm-telephone"]').setValue('(00) 91234-56790');
    await wrapper.get('[data-testid="button-confirm"]').trigger('click');

    expect(wrapper.get('#error-confirm-telephone').isVisible()).toBeTruthy();
  });

  it('O usuário deve concordar com os termos', async () => {
    expect(wrapper.find('#error-terms').exists()).toBeFalsy();

    await wrapper.get('[data-testid="input-terms"]').setValue(false);
    await wrapper.get('[data-testid="button-confirm"]').trigger('click');

    expect(wrapper.get('#error-terms').isVisible()).toBeTruthy();
  });

  it('Ao preencher todos os campos corretamente, o usuário deverá receber os dados através do evento confirm', async () => {
    const form = {
      fullname: 'Valdeir Psr',
      email: 'test@valdeir.dev',
      telephone: '(00) 91234-5678',
      confirmTelephone: '(00) 91234-5678',
      terms: true,
    };

    await wrapper.get('[data-testid="input-full-name"]').setValue(form.fullname);
    await wrapper.get('[data-testid="input-email"]').setValue(form.email);
    await wrapper.get('[data-testid="input-telephone"]').setValue(form.telephone);
    await wrapper.get('[data-testid="input-confirm-telephone"]').setValue(form.confirmTelephone);
    await wrapper.get('[data-testid="input-terms"]').setValue(form.terms);
    await wrapper.get('[data-testid="button-confirm"]').trigger('click');

    expect(wrapper.findAll('[id^="error-"]').length).toBe(0);
  });
});
