import React, { useState, useEffect } from 'react'
import Link from 'next/link'
import { useRouter } from 'next/router'
import Box from "@mui/material/Box"
import List from "@mui/material/List"
import ListItem from "@mui/material/ListItem"
import ListItemIcon from "@mui/material/ListItemIcon"
import ListItemText from "@mui/material/ListItemText"
import Typography from "@mui/material/Typography"

const LayoutSideBarItems = ({ sidebarItems }) => {
  const router = useRouter()
  const [current, setCurrent] = useState(router.pathname)

  useEffect(() => {
    if (router && router.pathname !== current) {
      setCurrent(router.pathname)
    }
  }, [router, current])


  return (
    <Box sx={ { width: 230 } } role='presentation'>
      <List sx={ { mt: 2.5 } }>
        { sidebarItems.map((sidebarItem, index) => (
          <Box key={ index } >
            { !sidebarItem.link ? (
              <Typography variant="caption" component="span" sx={ { display: "flex", fontSize: 14, fontWeight: "bold", color: "grey.900", py: 1 } }>
                { sidebarItem.title }
              </Typography>
            ) : (
              <Link href={ sidebarItem.link }>
                <ListItem
                  button
                  selected={ current === sidebarItem.link }
                  component="a"
                  sx={ {
                    mb: 0.5,
                    borderRadius: 4,
                    '&.Mui-selected': {
                      backgroundColor: 'primary.bg',
                      color: 'primary.main',
                    },
                    '&:hover': {
                      backgroundColor: 'primary.bg',
                      color: 'primary.main',
                    },
                  } }>
                  <ListItemIcon sx={ { color: "inherit", minWidth: '40px' } }>{ sidebarItem.icon }</ListItemIcon>
                  <ListItemText primary={ sidebarItem.title } />
                </ListItem>
              </Link>
            ) }
          </Box>
        )) }
      </List>
    </Box >
  )
}

export default LayoutSideBarItems
