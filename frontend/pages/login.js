import Link from 'next/link'
import { useRouter } from 'next/router'
import { useContext, useState } from 'react'
import Typography from '@mui/material/Typography'
import Box from "@mui/material/Box"
import TextField from '@mui/material/TextField'
import Checkbox from '@mui/material/Checkbox'
import Button from '@mui/material/Button'
import LoadingButton from '@mui/lab/LoadingButton'
import FormControlLabel from '@mui/material/FormControlLabel'
import BarChartRoundedIcon from '@mui/icons-material/BarChartRounded'
import Alert from '@mui/material/Alert'
import { grey } from '@mui/material/colors'
import api from '../src/api'
import { GlobalContext } from '../components/contexts/GlobalContext'

export default function Login() {
  const router = useRouter()
  const [state, setState] = useContext(GlobalContext)
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [remember, setRemember] = useState(true)
  const [errors, setErrors] = useState({
    alerts: [],
  })

  const handleLogin = async (e) => {
    e.preventDefault()
    setState(prevState => ({ ...prevState, loading: true }))
    setErrors({ ...errors, alerts: [] })
    const requestBody = { email, password }
    api().get('/sanctum/csrf-cookie').then(() => {
      api().post('api/v1/login', requestBody).then(response => {
        setState(prevState => ({ ...prevState, loading: false }))
        if (response.data && response.data.isSuccess) {
          const { token, user } = response.data.data
          const isLogin = true
          localStorage.setItem('dse_isLogin', isLogin)
          localStorage.setItem('dse_token', token)
          localStorage.setItem('dse_user', JSON.stringify(user))
          setState(prevState => ({ ...prevState, auth: { isLogin, user, token } }))
          router.push("/")
        }
      }).catch(function (error) {
        setState(prevState => ({ ...prevState, loading: false }))
        if (error.response) {
          // Request made and server responded
          if (error.response.data.errors) {
            setErrors({ ...errors, ...error.response.data.errors })
          }
          if (error.response.data.error) {
            setErrors({ ...errors, alerts: [error.response.data.error] })
          }
        } else if (error.request) {
          // The request was made but no response was received
          console.log(error.request)
        } else {
          // Something happened in setting up the request that triggered an Error
          console.log('Error', error.message)
        }
        setState
      })
    })
  }

  const authBg = {
    backgroundColor: 'primary.bg',
    width: "100%",
    minHeight: "100vh",
    display: "flex",
    flexDirection: "column",
    justifyContent: "center",
    alignItems: "center"
  }

  const authBox = {
    backgroundColor: 'white',
    width: 425,
    borderRadius: 3,
    border: "1px solid rgba(144, 202, 249, 0.46)",
    p: 5,
    my: 5
  }

  const handleEmailChange = (event) => {
    setErrors({ ...errors, email: undefined })
    setEmail(event.target.value)
  }

  const handlePasswordChange = (event) => {
    setErrors({ ...errors, password: undefined })
    setPassword(event.target.value)
  }


  return (
    <Box sx={ authBg }>
      <Box component="form" onSubmit={ handleLogin } sx={ authBox }>
        <Box sx={ { display: "flex", justifyContent: "center", mb: 2.5 } }>
          <BarChartRoundedIcon sx={ { color: 'primary.main' } } />
          <Typography variant='body1' component='h1' sx={ { ml: 1, fontWeight: 'bold' } }>
            Dhaka Stock Exchange
          </Typography>
        </Box>
        <Typography component="h2" variant="h5" align="center" gutterBottom sx={ { color: "primary.main", fontWeight: "bold" } }>Hi, Welcome Back</Typography>
        <Typography component="p" variant="body1" align="center" sx={ { color: grey[500], mb: 4 } }>Enter your credentials to continue</Typography>
        { errors.alerts && errors.alerts.map((alert, index) => <Alert severity="error" sx={ { mb: 3 } } key={ index }>{ alert }</Alert>) }
        <TextField
          fullWidth
          type="email"
          id="login_email"
          label="Email address"
          variant="outlined"
          sx={ { mb: 3 } }
          value={ email }
          onChange={ handleEmailChange }
          error={ Boolean(errors.email) }
          helperText={ errors.email }
        />
        <TextField
          fullWidth
          type="password"
          id="login_password"
          label="Password"
          variant="outlined"
          sx={ { mb: 2 } }
          value={ password }
          onChange={ handlePasswordChange }
          error={ Boolean(errors.password) }
          helperText={ errors.password }
        />
        <Box sx={ { display: "flex", justifyContent: "space-between", mb: 2 } }>
          <FormControlLabel control={ <Checkbox checked={ remember } onChange={ e => setRemember(!remember) } /> } label="Remember me" sx={ { color: grey[500] } } />
          <Button variant="text">Forgot Password?</Button>
        </Box>
        <LoadingButton
          type="submit"
          variant="contained"
          fullWidth sx={ { mb: 2 } }
          loading={ state.loading }
        >
          Sign In
        </LoadingButton>
        <Link href="/register">
          <Button variant="text" fullWidth>Don't have an account?</Button>
        </Link>
      </Box>
    </Box>
  )
}
