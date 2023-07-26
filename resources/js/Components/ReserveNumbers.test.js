import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';
import ReserveNumbers from './ReserveNumbers.vue';

const propsDefault = {
  price: 0.3,
  buyMax: 10,
  buyMin: 1,
};

describe('Teste de interação do componente', () => {
  it('Verifica as funções de incrementar e diminuir a quantidade através dos botoões + e -', async () => {
    const wrapper = mount(ReserveNumbers, {
      props: propsDefault,
    });

    // Valor inicial
    expect(wrapper.get('[data-testid="price-total"]').element.innerHTML).toEqual('Valor total: R$0,30');

    // Clica em aumentar a quantidade 1 vez
    await wrapper.get('[data-testid="increment"]').trigger('click');
    expect(wrapper.get('[data-testid="price-total"]').element.innerHTML).toEqual('Valor total: R$0,60');

    // Clica em diminuir a quantidade
    await wrapper.get('[data-testid="decrement"]').trigger('click');
    expect(wrapper.get('[data-testid="price-total"]').element.innerHTML).toEqual('Valor total: R$0,30');
  });

  it('A quantidade mínima não deve ser inferior a 3', async () => {
    const wrapper = mount(ReserveNumbers, {
      props: {
        ...propsDefault,
        buyMin: 3,
      },
    });

    // Valor inicial
    expect(wrapper.get('input').element.value).toEqual('3');
    // Clica em diminuir a quantidade
    await wrapper.get('[data-testid="decrement"]').trigger('click');
    expect(wrapper.get('input').element.value).toEqual('3');
  });

  it('O preço total deve ser alterado quando o usuário preencher diretamente o campo', async () => {
    const wrapper = mount(ReserveNumbers, {
      props: propsDefault,
    });

    // Valor inicial
    expect(wrapper.get('[data-testid="price-total"]').element.innerHTML).toEqual('Valor total: R$0,30');

    await wrapper.get('[data-testid="quantity"]').setValue(3);
    expect(wrapper.get('[data-testid="price-total"]').element.innerHTML).toEqual('Valor total: R$0,90');
  });

  it('Ao confirmar, o componente deve emitir a quantidade escolhida pelo usuário', async () => {
    const wrapper = mount(ReserveNumbers, {
      props: propsDefault,
    });

    await wrapper.get('[data-testid="quantity"]').setValue(3);
    await wrapper.get('[data-testid="confirm"]').trigger('click');

    const event = wrapper.emitted('reserveNumbers');

    expect(event).toHaveLength(1);
    expect(event[0]).toEqual([3]);
  });

  it('O campo não pode receber letras ou caracteres especiais', async () => {
    const wrapper = mount(ReserveNumbers, {
      props: propsDefault,
    });

    await wrapper.get('[data-testid="quantity"]').setValue('6a*8');
    await wrapper.get('[data-testid="confirm"]').trigger('click');

    const event = wrapper.emitted('reserveNumbers');

    expect(event).toHaveLength(1);
    expect(event[0]).toEqual([1]);
  });
});

describe('Compara snapshot', () => {
  it('Testa mudança no html', async () => {
    const wrapper = mount(ReserveNumbers, {
      props: propsDefault,
    });

    expect(wrapper.html()).toMatchSnapshot();
  });
});
