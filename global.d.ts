declare type Order = {
  id: number,
  customer_fullname: string,
  customer_email: string,
  customer_telephone: string,
  numbers_reserved: string[],
  status: string,
  expire_at: string|null,
  created_at: string,
  updated_at: string,
  rifa_id: number
}

declare type Payment = {
  ticket_url: string,
  order_id: number
}

declare type OrderWithPayment = Order & {
  payment: Payment
}

declare type OrderStatuses = {
  expired: string,
  paid: string,
  reserved: string,
  unknown: string,
  [k: string]: string
}

declare type Ranking = {
  name: string,
  total: number
}

declare type Rifa = {
  id : number,
  title: string,
  thumbnail: string,
  price: number,
  description: string,
  slug: string,
  total_numbers_available: number,
  buy_max: number,
  buy_min: number,
  raffle: string,
  status: string,
  expired_at: string|null,
  created_at: string,
  updated_at: string,
}

declare type FormReserveNumbers = {
  fullname: string,
  email: string,
  telephone: string,
  confirmTelephone: string,
  terms: boolean
}
