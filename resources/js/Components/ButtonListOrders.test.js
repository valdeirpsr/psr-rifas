import { mount } from "@vue/test-utils";
import { describe, expect, it } from "vitest";
import ButtonListOrders from './ButtonListOrders.vue';
import PsrDialog from './PsrDialog.vue';
import PsrLoading from './PsrLoading.vue';

describe('Teste de renderização do componente', () => {
    it('O botão de pesquisar deve aparecer quando o usuário informar o número de telefone', async () => {
        const wrapper = mount(ButtonListOrders);

        await wrapper.get('button').trigger('click');
        const dialogComponent = wrapper.getComponent(PsrDialog);

        expect(dialogComponent.find('[data-testid="button-search-orders"]').isVisible()).toBeFalsy();
        await dialogComponent.get('[data-testid="input-telephone"]').setValue('(71) 9 0000-0000');
        expect(dialogComponent.find('[data-testid="button-search-orders"]').isVisible()).toBeTruthy();
    });

    it('Ao clicar em Buscar, o componente de loading deverá ser exibido', async () => {
        const wrapper = mount(ButtonListOrders);
        await wrapper.get('button').trigger('click');
        const dialogComponent = wrapper.getComponent(PsrDialog);

        expect(wrapper.findComponent(PsrLoading).exists()).toBeFalsy();
        await dialogComponent.get('[data-testid="input-telephone"]').setValue('(71) 9 0000-0000');
        await dialogComponent.get('[data-testid="button-search-orders"]').trigger('click');
        expect(wrapper.findComponent(PsrLoading).exists()).toBeTruthy();
    });
})
