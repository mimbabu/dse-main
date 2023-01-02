import Table from '@mui/material/Table'
import TableBody from '@mui/material/TableBody'
import TableCell from '@mui/material/TableCell'
import TableContainer from '@mui/material/TableContainer'
import TableHead from '@mui/material/TableHead'
import TableRow from '@mui/material/TableRow'
import TableFooter from '@mui/material/TableFooter'
import Paper from '@mui/material/Paper'
import { useContext, useEffect } from "react"
import { GlobalContext } from "../../components/contexts/GlobalContext"
import Layout from "../../components/Layout"
import LayoutCard from "../../components/LayoutCard"
import Link from 'next/link'
import { fetchGetData } from '../../src/helper'

const sector = () => {
  const [state, setState] = useContext(GlobalContext)

  useEffect(() => {
    const getData = async () => {
      setState(prevState => ({ ...prevState, loading: true }))
      const sectors = await fetchGetData(`sector`)
      setState(prevState => ({
        ...prevState,
        loading: false,
        sectors: sectors ? sectors.data.sectors : []
      }))
    }
    getData()
  }, [])

  return (
    <Layout>
      <LayoutCard title="All Sector">
        <TableContainer component={ Paper }>
          <Table sx={ { minWidth: 650 } } aria-label="Sector table" stickyHeader>
            <TableHead>
              <TableRow>
                <TableCell>Name</TableCell>
                <TableCell>Quantity</TableCell>
              </TableRow>
            </TableHead>
            <TableBody>
              { state.sectors.map((row) => (
                <Link href={ `/sector/${row.id}` } key={ row.id }>
                  <TableRow sx={ { cursor: "pointer", "&:hover": { backgroundColor: "primary.bg" } } }>
                    <TableCell>
                      { row.name ? row.name : "(Unknown)" }
                    </TableCell>
                    <TableCell>{ row.companies_count }</TableCell>
                  </TableRow>
                </Link>
              )) }
            </TableBody>
            <TableFooter>
              <TableRow>
                <TableCell variant="head">Total</TableCell>
                <TableCell variant="head">{ state.sectors.map(sector => sector.companies_count).reduce((a, b) => a + b, 0) }</TableCell>
              </TableRow>
            </TableFooter>
          </Table>
        </TableContainer>
      </LayoutCard>
    </Layout>
  )
}

export default sector
