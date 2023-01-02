import { useRouter } from 'next/router'

export default function Index() {
  const router = useRouter()

  if (process.browser) {
    localStorage.removeItem('dse_isLogin')
    localStorage.removeItem('dse_user')
    localStorage.removeItem('dse_token')
    router.push("/login")
  }

  return null
}
