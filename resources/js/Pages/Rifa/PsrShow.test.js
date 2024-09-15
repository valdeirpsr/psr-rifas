import { describe, expect, it } from 'vitest';
import { mount } from '@vue/test-utils';
import PsrShow from './PsrShow.vue';
import PsrRanking from '../../Components/PsrRanking.vue';
import { ZiggyVue } from '../../../../vendor/tightenco/ziggy/dist/vue.m';
import { Ziggy } from '../../ziggy';
import ReserveNumbers from '../../Components/ReserveNumbers.vue';

describe('Teste com rifa expirada e finalizadas', () => {
  it('O formulário para escolher a quantidade de bilhete não deve aparecer com a rifa finalizada', () => {
    const wrapper = mount(PsrShow, {
      props: {
        rifa: {
          id: 1,
          title: 'First Draw',
          thumbnail: 'J7P03ilHENBApaoVcUCX2dovCHqyON-metacG5nLnBuZw==-.png',
          price: 0.54,
          description:
            '<p>Vamos sortear <strong>5 telefones</strong> do 1 até o 5 <strong>prêmio</strong>, dias dos pais vamos poder da oportunidade ferver 5 ganhadores na ação dos dias dos pais, essa ação tem como <strong>objetivo </strong>a oportunidade do público apoiar a carreira do Andresson Costa o sucesso das ruas, o sorteio acontece dia 9 de agosto para dias dos pais , obrigado e boa sorte&nbsp;</p>',
          slug: 'first-draw-KYNyqbCdDb',
          total_numbers_available: 100000,
          buy_max: 300,
          buy_min: 1,
          raffle: 'Loteria Federal',
          status: 'finished',
          expired_at: '1993-07-13',
        },
        ranking: [],
        winners: [],
      },
      global: {
        plugins: [[ZiggyVue, Ziggy]],
      },
    });

    expect(wrapper.findComponent(ReserveNumbers).exists()).toBeFalsy();
  });

  it('A lista de ganhadores deve aparecer na ordem crescente de acordo com a posição', () => {
    const wrapper = mount(PsrShow, {
      props: {
        rifa: {
          id: 1,
          title: 'First Draw',
          thumbnail: 'J7P03ilHENBApaoVcUCX2dovCHqyON-metacG5nLnBuZw==-.png',
          price: 0.54,
          description:
            '<p>Vamos sortear <strong>5 telefones</strong> do 1 até o 5 <strong>prêmio</strong>, dias dos pais vamos poder da oportunidade ferver 5 ganhadores na ação dos dias dos pais, essa ação tem como <strong>objetivo </strong>a oportunidade do público apoiar a carreira do Andresson Costa o sucesso das ruas, o sorteio acontece dia 9 de agosto para dias dos pais , obrigado e boa sorte&nbsp;</p>',
          slug: 'first-draw-KYNyqbCdDb',
          total_numbers_available: 100000,
          buy_max: 300,
          buy_min: 1,
          raffle: 'Loteria Federal',
          status: 'finished',
          expired_at: '1993-07-13',
        },
        ranking: [],
        winners: [
          {
            customer_fullname: 'PSR',
            video: null,
            position: 2,
          },
          {
            customer_fullname: 'Valdeir',
            video: null,
            position: 1,
          },
        ],
      },
      global: {
        plugins: [[ZiggyVue, Ziggy]],
      },
    });

    expect(wrapper.get('[data-testid="winners-list"]').element.firstElementChild.textContent).toContain('Valdeir');
  });
});

describe('Teste com o componente de ranking de compradores', () => {
  it.each([true, false])(
    'O componente PsrRanking deve aparecer somente quando a propriedade ranking_buyer for true',
    (rankingBuyerBoolean) => {
      const wrapper = mount(PsrShow, {
        props: {
          rifa: {
            id: 1,
            title: 'First Draw',
            thumbnail: 'J7P03ilHENBApaoVcUCX2dovCHqyON-metacG5nLnBuZw==-.png',
            price: 0.54,
            description: '',
            slug: 'first-draw-KYNyqbCdDb',
            total_numbers_available: 100000,
            buy_max: 300,
            buy_min: 1,
            ranking_buyer: rankingBuyerBoolean,
            raffle: 'Loteria Federal',
            status: 'finished',
            expired_at: '1993-07-13',
          },
          ranking: [
            {
              customer_fullname: 'Customer Name',
              total_numbers: 1234,
            },
          ],
          winners: [],
        },
        global: {
          plugins: [[ZiggyVue, Ziggy]],
        },
      });

      if (rankingBuyerBoolean) {
        expect(wrapper.findComponent(PsrRanking).exists()).toBeTruthy();
      } else {
        expect(wrapper.findComponent(PsrRanking).exists()).toBeFalsy();
      }
    }
  );
});
