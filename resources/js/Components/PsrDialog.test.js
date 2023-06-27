import { it, describe, expect } from "vitest";
import { mount } from "@vue/test-utils";
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
});

describe('Compara snapshot', () => {
    it('Testa mudança no html', async () => {
        const wrapper = mount(PsrDialog);

        expect(wrapper.html()).toMatchSnapshot();
    })
});
