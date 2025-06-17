export interface Contact {
  id: number
  name: string
  phone: string
  company: string
  role: string
}

export interface ContactFilters {
  company: string
  role: string
}

export interface ContactState {
  contacts: Contact[]
  loading: boolean
  error: string | null
}