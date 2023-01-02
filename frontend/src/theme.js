import { createTheme } from "@mui/material/styles"
import { red } from "@mui/material/colors"

// Create a theme instance.
const theme = createTheme({
  palette: {
    primary: {
      main: "#673bb7",
      bg: "#e3f2fd",
      btnBg: "#ede7f6",
    },
    secondary: {
      main: "#673bb7",
      text: "#b2bac2",
    },
    error: {
      main: red.A400,
    },
  },
})

export default theme
