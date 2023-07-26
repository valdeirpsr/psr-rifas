export const useTelephone = (value: string) =>
  value.replace(/\D/g, '').length === 10 || value.replace(/\D/g, '').length === 11;
