import Card from '@mui/material/Card'
import CardContent from '@mui/material/CardContent'
import CardHeader from '@mui/material/CardHeader'
import Divider from "@mui/material/Divider"

const LayoutCard = ({ title, children, width, height }) => {
  return (
    <Card sx={ { border: "1px solid rgba(144, 202, 249, 0.46)", borderRadius: 4, width, height } } elevation={ 0 }>
      <CardHeader title={ title } titleTypographyProps={ { fontSize: 18, fontWeight: 'bold', mt: 1.5, mb: 0, ml: 2 } } />
      <Divider light={ true } />
      <CardContent>
        { children }
      </CardContent>
    </Card>
  )
}

LayoutCard.defaultProps = {
  width: '100%',
  height: 'auto',
}

export default LayoutCard
