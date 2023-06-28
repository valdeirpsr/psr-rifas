import { helpers } from '@vuelidate/validators';

type GenericObject = { [key: string]: unknown };

export const useSameAs = <K extends keyof T, T extends GenericObject>(obj: T, param: K, fieldName: string) =>
  helpers.withMessage(`O campo deve ser igual ao ${fieldName}`, (value) => obj[param] === value);

export const useFullname = () => helpers.regex(/[a-záàâãéèêíïóôõöúçñü]{3,}\s[a-záàâãéèêíïóôõöúçñü\s]{3,}/gi);

export const useTelephone = (value: string) => value.replace(/\D/g, '').length === 10 || value.replace(/\D/g, '').length === 11;
