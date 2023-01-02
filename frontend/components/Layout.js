import Head from 'next/head'
import { useContext, useState } from 'react'
import Box from "@mui/material/Box"
import LayoutSideBar from './LayoutSideBar'
import LayoutAppBar from './LayoutAppBar'
import { GlobalContext } from './contexts/GlobalContext'

const Layout = ({ children }) => {
  const [state, setState] = useContext(GlobalContext)
  const sideBar = state.sideBar

  const setSideBar = (sideBar) => {
    setState(prevState => ({ ...prevState, sideBar }))
  }

  return (
    <Box sx={ { height: "100vh", display: "flex", flexDirection: "column" } }>
      <Head>
        <title>Dhaka Stock Exchange</title>
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, shrink-to-fit=no" />
        <meta http-equiv="X-UA-Compatible" content="IE=7" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <meta name="keywords" content="dse, dhaka stock exchange. stock, stock exchange, stock exchange bd, stock exchange bangladesh, bd stock, bangladesh stock, stock market, stock market bangladesh, bangladesh stock market, share bazzar" />
        <meta name="description" content="DSE is the largest stock exchange in Bangladesh. We are making this project to help you to get information about the stock market. You can analysis the stock market with the help of this tool." />
      </Head>
      <LayoutAppBar sideBar={ sideBar } setSideBar={ setSideBar } />
      <Box sx={ { flexGrow: 1, display: "flex", mx: 2.5 } }>
        <LayoutSideBar sideBar={ sideBar } />
        <Box sx={ { flexGrow: 1, p: 2.5, backgroundColor: 'primary.bg', borderRadius: 4, transition: "width 200ms cubic-bezier(0.4, 0, 0.6, 1) 0ms" } }>
          { children }
        </Box>
      </Box>
    </Box>
  )
}

export default Layout
