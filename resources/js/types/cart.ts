export interface Product {
  id: number;
  name: string;
  description: string;
  price: number;
  sale?: number | null;
  uri: string;
  category_id?: number;
  quantity?: number;
}

export interface Category {
  id: number;
  name: string;
}

export interface Tenant {
  id: number;
  name: string;
  whatsapp: string;
  logo: string;
  cover: string;
  custom_button?: string;
  custom_button_text?: string;
  custom_title_color?: string;
}