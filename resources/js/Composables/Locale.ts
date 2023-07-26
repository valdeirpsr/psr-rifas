import { toValue } from '@vueuse/core';

export function useLocaleDateLong(value: string | Date) {
  const date = value instanceof Date ? toValue(value) : new Date(toValue(value));

  return date.toLocaleDateString('pt-br', {
    year: 'numeric',
    month: 'long',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
  });
}

export function useLocaleCurrency(value: number) {
  return toValue(value)
    .toLocaleString('pt-br', {
      currency: 'BRL',
      style: 'currency',
    })
    .replace(/\s/g, '');
}

export function useLocaleTelephone(value: string) {
  return value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
}
