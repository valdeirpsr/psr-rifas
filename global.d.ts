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
