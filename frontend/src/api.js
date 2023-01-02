import axios from 'axios'
// import { logOut } from '@/util/auth'

export default function api() {
  const api = axios.create({
    baseURL: process.env.NEXT_PUBLIC_BASE_URL,
    withCredentials: true
  })
  axios.defaults.withCredentials = true

  // api.interceptors.response.use(response => response, error => {
  //   if (error.response.status === 401) {
  //     // logOut()

  //     return Promise.reject()
  //   }

  //   return Promise.reject(error)
  // })

  return api
}
