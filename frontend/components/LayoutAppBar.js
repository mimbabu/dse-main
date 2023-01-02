import { useState } from 'react'
import Link from 'next/link'
import Box from "@mui/material/Box"
import AppBar from "@mui/material/AppBar"
import Toolbar from "@mui/material/Toolbar"
import IconButton from "@mui/material/IconButton"
import Typography from "@mui/material/Typography"
import MenuIcon from "@mui/icons-material/Menu"
import BarChartRoundedIcon from '@mui/icons-material/BarChartRounded'
import Avatar from '@mui/material/Avatar'
import Menu from '@mui/material/Menu'
import MenuItem from '@mui/material/MenuItem'
import ListItemText from '@mui/material/ListItemText'
import ListItemIcon from '@mui/material/ListItemIcon'
import PersonOutlinedIcon from '@mui/icons-material/PersonOutlined'
import SettingsOutlinedIcon from '@mui/icons-material/SettingsOutlined'
import DashboardOutlinedIcon from '@mui/icons-material/DashboardOutlined'
import LogoutOutlinedIcon from '@mui/icons-material/LogoutOutlined'

const LayoutAppBar = ({ sideBar, setSideBar }) => {
  const [anchorElUser, setAnchorElUser] = useState(null)
  const settings = [
    {
      title: 'Profile',
      icon: <PersonOutlinedIcon fontSize='small' />,
      link: '/profile',
    },
    {
      title: 'Account',
      icon: <SettingsOutlinedIcon fontSize='small' />,
      link: '/account',
    },
    {
      title: 'Dashboard',
      icon: <DashboardOutlinedIcon fontSize='small' />,
      link: '/',
    },
    {
      title: 'Logout',
      icon: <LogoutOutlinedIcon fontSize='small' />,
      link: '/logout',
    },
  ]
  const toggleDrawer = () => (event) => {
    if (
      event.type === "keydown" &&
      (event.key === "Tab" || event.key === "Shift")
    ) {
      return
    }
    setSideBar(!sideBar)
  }

  const handleOpenUserMenu = (event) => {
    setAnchorElUser(event.currentTarget)
  }

  const handleCloseUserMenu = () => {
    setAnchorElUser(null)
  }

  return (
    <AppBar position='static' color='inherit' elevation={ 0 }>
      <Toolbar>
        <Box sx={ { display: "flex", width: 230, mr: 2.5 } }>
          <BarChartRoundedIcon sx={ { color: 'primary.main' } } />
          <Typography variant='body1' component='h1' sx={ { ml: 1, fontWeight: 'bold' } }>
            Dhaka Stock Exchange
          </Typography>
        </Box>
        <Box sx={ { flexGrow: 1, display: "flex", alignItems: "center" } }>
          <IconButton
            onClick={ toggleDrawer() }
            size='large'
            edge='start'
            color='primary'
            aria-label='menu'
            sx={ {
              p: 0.9,
              borderRadius: 2,
              ml: 0.01,
              mr: "auto",
              backgroundColor: 'primary.btnBg',
              '&:hover': {
                backgroundColor: 'primary.main',
                color: 'white',
              }
            } }>
            <MenuIcon fontSize="small" />
          </IconButton>
          <IconButton onClick={ handleOpenUserMenu } sx={ {
            p: 0.5,
            minWidth: 0,
            borderRadius: 50,
            backgroundColor: 'primary.btnBg',
            '&:hover': {
              backgroundColor: 'primary.main',
              color: 'white',
            }
          } } >
            <Avatar alt="Ridwan Malik" src="https://picsum.photos/200/200.webp" sx={ { width: 34, height: 34 } } />
          </IconButton>
          <Menu
            sx={ { mt: '45px' } }
            id="menu-appbar"
            anchorEl={ anchorElUser }
            anchorOrigin={ {
              vertical: 'top',
              horizontal: 'right',
            } }
            keepMounted
            transformOrigin={ {
              vertical: 'top',
              horizontal: 'right',
            } }
            open={ Boolean(anchorElUser) }
            onClose={ handleCloseUserMenu }
          >
            { settings.map((setting, index) => (
              <Link key={ index } href={ setting.link }>
                <MenuItem onClick={ handleCloseUserMenu }>
                  <ListItemIcon>
                    { setting.icon }
                  </ListItemIcon>
                  <ListItemText>{ setting.title }</ListItemText>
                </MenuItem>
              </Link>
            )) }
          </Menu>
        </Box>
      </Toolbar>
    </AppBar>
  )
}

export default LayoutAppBar
