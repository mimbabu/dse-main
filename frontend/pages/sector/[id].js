import { useRouter } from 'next/router'
import { useContext, useEffect } from 'react'
import Typography from '@mui/material/Typography'
import Table from '@mui/material/Table'
import TableBody from '@mui/material/TableBody'
import TableCell from '@mui/material/TableCell'
import TableContainer from '@mui/material/TableContainer'
import TableHead from '@mui/material/TableHead'
import TableRow from '@mui/material/TableRow'
import Paper from '@mui/material/Paper'
import { GlobalContext } from '../../components/contexts/GlobalContext'
import Layout from '../../components/Layout'
import LayoutCard from '../../components/LayoutCard'
import { fetchGetData } from '../../src/helper'

const SectorId = () => {
  const [state, setState] = useContext(GlobalContext)
  const router = useRouter()
  const { id } = router.query
  const sector = state.sectors.find(sector => sector.id == id)

  useEffect(() => {
    const getData = async () => {
      setState(prevState => ({ ...prevState, loading: true }))
      const companies = await fetchGetData(`sector/${id}/companies`)
      console.log(`Log | file: [id].js | line 25 | companies`, companies)
      setState(prevState => ({
        ...prevState,
        loading: false,
        companies: companies ? companies.data.companies : []
      }))
    }
    if (sector) {
      getData()
    }
  }, [sector])


  return (
    <Layout>
      <LayoutCard title={ sector ? `${sector.name} (${sector.companies_count})` : "Sector" }>
        <TableContainer component={ Paper }>
          <Table sx={ { minWidth: 650 } } aria-label="Sector table" stickyHeader>
            <TableHead>
              <TableRow>
                <TableCell>Code (Name)</TableCell>
                <TableCell>Market Capitalization (mn)</TableCell>
              </TableRow>
            </TableHead>
            <TableBody>
              { state.companies.map((row) => (
                <TableRow key={ row.id }>
                  <TableCell sx={ { display: "flex" } }>
                    <Typography sx={ { fontWeight: "bold", mr: 0.5 } }>
                      { row.code }
                    </Typography>
                    <Typography color="secondary">
                      ({ row.name })
                    </Typography>
                  </TableCell>
                  <TableCell>{ row.market_capitalization_mn }</TableCell>
                </TableRow>
              )) }
            </TableBody>
          </Table>
        </TableContainer>
      </LayoutCard>
    </Layout >
  )
}

export default SectorId
