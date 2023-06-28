import { nextTick } from 'vue';
import { describe, expect, it } from 'vitest';
import { mount, flushPromises } from '@vue/test-utils';
import FormReserveNumbers from './FormReserveNumbers.vue';

describe('Testa interação do formulário', () => {
    it('O nome e o sobrenome devem ter pelo menos 3 caracteres', async () => {
        const wrapper = mount(FormReserveNumbers);

        await wrapper.get('[data-testid="input-full-name"]').setValue('Oi Hi');
        await wrapper.get('[data-testid="button-confirm"]').trigger('click');

        expect(wrapper.get('#error-fullname').isVisible()).toBeTruthy();
    });

    it('O email deve ser válido', async () => {
        const wrapper = mount(FormReserveNumbers);

        await wrapper.get('[data-testid="input-email"]').setValue('test');
        await wrapper.get('[data-testid="button-confirm"]').trigger('click');

        expect(wrapper.get('#error-email').isVisible()).toBeTruthy();
    });

    it('A confirmação do telefone deve ser igual ao telefone', async () => {
        const wrapper = mount(FormReserveNumbers);

        await wrapper.get('[data-testid="input-telephone"]').setValue('(00) 91234-5678');
        await wrapper.get('[data-testid="input-confirm-telephone"]').setValue('(00) 91234-56790');
        await wrapper.get('[data-testid="button-confirm"]').trigger('click');

        expect(wrapper.get('#error-confirm-telephone').isVisible()).toBeTruthy();
    });

    it('O usuário deve concordar com os termos', async () => {
        const wrapper = mount(FormReserveNumbers);

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
        }

        const wrapper = mount(FormReserveNumbers);

        await wrapper.get('[data-testid="input-full-name"]').setValue(form.fullname);
        await wrapper.get('[data-testid="input-email"]').setValue(form.email);
        await wrapper.get('[data-testid="input-telephone"]').setValue(form.telephone);
        await wrapper.get('[data-testid="input-confirm-telephone"]').setValue(form.confirmTelephone);
        await wrapper.get('[data-testid="input-terms"]').setValue(form.terms);
        await wrapper.get('[data-testid="button-confirm"]').trigger('click');

        await flushPromises();
        const events = wrapper.emitted('confirm');
        expect(events).toHaveLength(1);
    })
});
