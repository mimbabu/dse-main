import Layout from "../components/Layout"
import DataGridTable from '../components/DataGridTable'
import LayoutCard from "../components/LayoutCard"
import moment from "moment"

export default function PriceEarning() {
  const today = moment()

  const columns = [
    { field: 'id', headerName: '#', type: "number", width: 70 },
    { field: 'company', headerName: 'Code', valueFormatter: ({ value }) => value.code, },
    { field: 'close_price', headerName: 'CLOSEP', type: "number", description: 'Close Price', },
    { field: 'ycp', headerName: 'YCP', type: "number", description: `Yesterday's  Close Price` },
    { field: 'pe_1', headerName: 'PE1', type: "number", },
    { field: 'pe_2', headerName: 'PE2', type: "number", },
    { field: 'pe_3', headerName: 'PE3', type: "number", },
    { field: 'pe_4', headerName: 'PE4', type: "number", },
    { field: 'pe_5', headerName: 'PE5', type: "number", },
    { field: 'pe_6', headerName: 'PE6', type: "number", },
    { field: 'data_updated_at_date', headerName: 'Date', type: "date", },
    { field: 'data_updated_at_time', headerName: 'Time' }
  ]

  return (
    <Layout>
      <LayoutCard title="Price Earning">
        <DataGridTable title="per" columns={ columns } url="price-earning" defaultData={ today } />
      </LayoutCard>
    </Layout>
  )
}
