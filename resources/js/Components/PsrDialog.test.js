import { it, describe, expect } from 'vitest';
import { mount } from '@vue/test-utils';
import PsrDialog from './PsrDialog.vue';

describe('Testa eventos do modal', () => {
  it('Ao clicar no botão cancelar, o evento dismiss deverá ser chamado', async () => {
    const wrapper = mount(PsrDialog);

    await wrapper.get('[data-testid="button-cancel"]').trigger('click');
    const event = wrapper.emitted('dismiss');

    expect(event).toHaveLength(1);
  });

  it('Ao clicar no botão confirmar, o evento confirm deverá ser chamado', async () => {
    const wrapper = mount(PsrDialog);

    await wrapper.get('[data-testid="button-confirm"]').trigger('click');
    const event = wrapper.emitted('confirm');

    expect(event).toHaveLength(1);
  });

  it('Os botões de cancelar e confirmar não devem aparecer quando for definido nos atributos do componente', () => {
    const wrapper = mount(PsrDialog, {
      props: {
        buttonCancel: false,
        buttonConfirm: false,
      },
    });

    expect(wrapper.find('[data-testid="button-cancel"]').exists()).toBeFalsy();
    expect(wrapper.find('[data-testid="button-confirm"]').exists()).toBeFalsy();
  });
});

describe('Compara snapshot', () => {
  it('Testa mudança no html', async () => {
    const wrapper = mount(PsrDialog);

    expect(wrapper.html()).toMatchSnapshot();
  });
});
