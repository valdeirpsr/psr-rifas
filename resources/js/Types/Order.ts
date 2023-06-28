export type OrderStatuses = {
  expired: string,
  paid: string,
  reserved: string,
  unknown: string,
  [k: string]: string
}

export type Order = {
  id: number,
  name: string,
  numbers: string[],
  payment_expire_at: number|null,
  status: keyof OrderStatuses
}
