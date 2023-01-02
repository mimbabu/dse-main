import PropTypes from 'prop-types'
import Head from 'next/head'
import { ThemeProvider } from '@mui/material/styles'
import CssBaseline from '@mui/material/CssBaseline'
import { CacheProvider } from '@emotion/react'
import { GlobalProvider } from "../components/contexts/GlobalContext"
import theme from '../src/theme'
import createEmotionCache from '../src/createEmotionCache'
import DateAdapter from '@mui/lab/AdapterMoment'
import LocalizationProvider from '@mui/lab/LocalizationProvider'
import '../styles/globals.css'
import { useRouter } from 'next/router'
import authRoutes from '../src/authRoutes'

// Client-side cache, shared for the whole session of the user in the browser.
const clientSideEmotionCache = createEmotionCache()

export default function MyApp(props) {
  const router = useRouter()
  const { Component, emotionCache = clientSideEmotionCache, pageProps } = props

  if (process.browser) {
    const isLogin = localStorage.getItem("dse_isLogin") === "true" ? true : false
    if (isLogin) {
      if (authRoutes.includes(router.asPath)) {
        router.push("/")
        return null
      }
    } else {
      if (!authRoutes.includes(router.asPath)) {
        router.push("/login")
        return null
      }
    }
  }

  return (
    <GlobalProvider>
      <LocalizationProvider dateAdapter={ DateAdapter }>
        <CacheProvider value={ emotionCache }>
          <Head>
            <meta name="viewport" content="initial-scale=1, width=device-width" />
          </Head>
          <ThemeProvider theme={ theme }>
            {/* CssBaseline kickstart an elegant, consistent, and simple baseline to build upon. */ }
            <CssBaseline />
            <Component { ...pageProps } />
          </ThemeProvider>
        </CacheProvider>
      </LocalizationProvider>
    </GlobalProvider>
  )
}

MyApp.propTypes = {
  Component: PropTypes.elementType.isRequired,
  emotionCache: PropTypes.object,
  pageProps: PropTypes.object.isRequired,
}
