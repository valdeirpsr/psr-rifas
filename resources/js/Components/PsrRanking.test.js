import { mount } from "@vue/test-utils";
import { describe, expect, it } from "vitest";
import Ranking from './PsrRanking.vue';

describe('Teste de renderização do componente', () => {
    it ('Os três usuários com maiores quantidades de número devem aparecer na ordem do que mais comprou para o que menos comprou', () => {
        const wrapper = mount(Ranking, {
            props: {
                users: [
                    {
                      name: "Valdeir",
                      total: 1
                    },
                    {
                      name: "Valdeir Sant'Anna",
                      total: 1000
                    },
                    {
                      name: "Valdeir PSR",
                      total: 500
                    },
                    {
                        name: "PSR",
                        total: 500
                    },
                ]
            }
        });

        const users = wrapper.findAll('[data-test="ranking-user"]');
        expect(users.length).toEqual(3);
        expect(users[0].text()).toEqual('🥇Valdeir Sant\'Anna1000 bilhetes');
    });
});

describe('Compara snapshot', () => {
    it('Testa mudança no html', async () => {
        const wrapper = mount(Ranking, {
            props: {
                users: [
                    {
                      name: "Valdeir",
                      total: 1
                    },
                    {
                      name: "Valdeir Sant'Anna",
                      total: 1000
                    },
                    {
                      name: "Valdeir PSR",
                      total: 500
                    }
                ]
            }
        });

        expect(wrapper.html()).toMatchSnapshot();
    })
});
