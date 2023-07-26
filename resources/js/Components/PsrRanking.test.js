import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';
import Ranking from './PsrRanking.vue';

describe('Teste de renderização do componente', () => {
  it('Os três usuários com maiores quantidades de número devem aparecer na ordem do que mais comprou para o que menos comprou', () => {
    const wrapper = mount(Ranking, {
      props: {
        users: [
          {
            customer_fullname: 'Valdeir',
            total_numbers: 1,
          },
          {
            customer_fullname: "Valdeir Sant'Anna",
            total_numbers: 1000,
          },
          {
            customer_fullname: 'Valdeir PSR',
            total_numbers: 500,
          },
          {
            customer_fullname: 'PSR',
            total_numbers: 500,
          },
        ],
      },
    });

    const users = wrapper.findAll('[data-test="ranking-user"]');
    expect(users.length).toEqual(3);
    expect(users[0].text()).toEqual("🥇Valdeir Sant'Anna1000 bilhetes");
  });
});

describe('Compara snapshot', () => {
  it('Testa mudança no html', async () => {
    const wrapper = mount(Ranking, {
      props: {
        users: [
          {
            customer_fullname: 'Valdeir',
            total_numbers: 1,
          },
          {
            customer_fullname: "Valdeir Sant'Anna",
            total_numbers: 1000,
          },
          {
            customer_fullname: 'Valdeir PSR',
            total_numbers: 500,
          },
        ],
      },
    });

    expect(wrapper.html()).toMatchSnapshot();
  });
});
