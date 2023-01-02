import React from 'react'
import { styled } from "@mui/material/styles"
import Paper from "@mui/material/Paper"
import { grey } from "@mui/material/colors"
import TrendingUpIcon from '@mui/icons-material/TrendingUp'
import PaidOutlinedIcon from '@mui/icons-material/PaidOutlined'
import ScheduleIcon from '@mui/icons-material/Schedule'
import DashboardOutlinedIcon from '@mui/icons-material/DashboardOutlined'
import StackedLineChartOutlinedIcon from '@mui/icons-material/StackedLineChartOutlined'
import TimelineOutlinedIcon from '@mui/icons-material/TimelineOutlined'
import LayoutSideBarItems from './LayoutSideBarItems'
import AppsOutlinedIcon from '@mui/icons-material/AppsOutlined'
const LayoutSideBar = ({ sideBar }) => {

  const Sidebar = styled(Paper)(({ theme }) => ({
    backgroundColor: theme.palette.mode === "dark" ? "#1A2027" : "#fff",
  }))

  const items = [
    {
      title: 'Dashboard',
    },
    {
      title: 'Dashboard',
      icon: <DashboardOutlinedIcon />,
      link: '/',
    },
    {
      title: 'Transactions',
    },
    {
      title: 'Latest Share Price',
      icon: <TrendingUpIcon />,
      link: '/latest-share-price',
    },
    {
      title: 'Price Earning Ratio',
      icon: <PaidOutlinedIcon />,
      link: '/price-earning',
    },
    {
      title: 'Day End Summary',
      icon: <ScheduleIcon />,
      link: '/day-end-summary',
    },
    {
      title: 'Custom Data',
      icon: <StackedLineChartOutlinedIcon />,
      link: '/custom-data',
    },
    {
      title: 'Data',
    },
    {
      title: 'All Sector',
      icon: <AppsOutlinedIcon />,
      link: '/sector',
    },
    {
      title: 'Charts',
    },
    {
      title: 'Day End Summary',
      icon: <TimelineOutlinedIcon />,
      link: '/day-end-summary-charts',
    },
  ]

  return (
    <Sidebar
      square
      elevation={ 0 }
      sx={ {
        display: sideBar ? "block" : "none",
        color: grey[700],
        mr: 2.5
      } }>
      <LayoutSideBarItems sidebarItems={ items } />
    </Sidebar>
  )
}

export default LayoutSideBar
