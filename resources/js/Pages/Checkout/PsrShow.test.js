import { describe, expect, it } from 'vitest';
import { mount } from "@vue/test-utils";
import PsrShow from './PsrShow.vue';
import PsrBadge from '@Components/PsrBadge.vue';

const getValidData = () => {
    const expire_at = new Date(Date.now() + 3600).toLocaleString('af');

    return {
        order: {
            "id":535,
            "customer_fullname": "Valdeir Psr",
            "customer_email": "test@test.com",
            "customer_telephone": "11111111111",
            "numbers_reserved": ["2290","29400","49869","58339","84963"],
            "status": "reserved",
            "expire_at": expire_at,
            "transaction_amount": 2.7
        },
        rifa: {
            "id":1,
            "title": "First Draw",
            "price": 0.54
        },
    }
}

describe('Testa renderização do layout', () => {
    const selectorNumbers = '[data-testid="numbers"]';
    const selectorButtonToggleNumbers = '[data-testid="button-toggle-numbers"]';

    it('Testa se os números são exibidos ao clicar em "Ver números"', async () => {
        const wrapper = mount(PsrShow, {
            props: getValidData()
        });

        expect(wrapper.find(selectorNumbers).exists()).toBeFalsy();
        await wrapper.get(selectorButtonToggleNumbers).trigger('click');
        expect(wrapper.find(selectorNumbers).exists()).toBeTruthy();
    });

    it('Verifica se os números são renderizados corretamente', async () => {
        const wrapper = mount(PsrShow, {
            props: getValidData()
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
                order: {"id":535,"customer_fullname":"Valdeir Psr","customer_email":"test@test.com","customer_telephone":"11111111111","numbers_reserved":["2290","29400","49869","58339","84963"],"status":"paid","expire_at":"2023-07-04 23:59:59","transaction_amount":2.7},
                rifa: {"id":1,"title":"First Draw","price":0.54},
            }
        });

        expect(wrapper.find('[data-testid="expire"]').exists()).toBeFalsy();
        expect(wrapper.find('[data-testid="countdown"]').exists()).toBeFalsy();
        expect(wrapper.find('[data-testid="button-payment"]').exists()).toBeFalsy();
    });
});
