import { createContext, useEffect, useState } from "react"
import { useRouter } from "next/router"
import authRoutes from "../../src/authRoutes"

export const GlobalContext = createContext()

export const GlobalProvider = (props) => {
  const router = useRouter()
  const initialState = {
    loading: true,
    auth: {
      isLogin: false,
      user: null,
      token: ""
    },
    sideBar: true,
    rowsPerPageOptions: [5, 10, 25, 50, 100],
    columnVisibilityModel: {},
    data: {},
    lsp: {},
    per: {},
    des: {},
    sectors: [],
    companies: [],
    chart: {
      des: [],
    },
  }

  const [state, setState] = useState(initialState)

  useEffect(() => {

    const handleStop = (url) => {
      setState(prevState => ({ ...prevState, columnVisibilityModel: {} }))
    }

    router.events.on('routeChangeComplete', handleStop)

    return () => {
      router.events.off('routeChangeComplete', handleStop)
    }
  }, [router])


  useEffect(() => {
    // fetchData
    const getData = async () => {

      const isLogin = localStorage.getItem("dse_isLogin") === "true" ? localStorage.getItem("dse_isLogin") === "true" ? true : false : false
      const user = JSON.parse(localStorage.getItem("dse_user"))
      const token = localStorage.getItem("dse_token")

      if (isLogin) {
        if (authRoutes.includes(router.asPath)) {
          router.push("/")
        }
        setState(prevState => ({
          ...prevState,
          loading: false,
          auth: {
            isLogin: true,
            user: user,
            token: token
          }
        }))
      } else {
        if (!authRoutes.includes(router.asPath)) {
          router.push("/login")
        }
        setState(prevState => ({
          ...prevState,
          loading: false,
          auth: {
            isLogin: false,
            user: null,
            token: ""
          }
        }))
      }

    }

    getData()

    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])

  return (
    <GlobalContext.Provider value={ [state, setState] }>
      { props.children }
    </GlobalContext.Provider>
  )
}
