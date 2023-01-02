import Layout from "../components/Layout"
import DataGridTable from '../components/DataGridTable'
import LayoutCard from "../components/LayoutCard"
import moment from "moment"

export default function LatestSharePrice() {
  const today = moment()

  const columns = [
    { field: 'id', headerName: '#', type: "number", width: 70 },
    { field: 'company', headerName: 'Code', valueFormatter: ({ value }) => value.code, },
    { field: 'ltp', headerName: 'LTP', type: "number", description: 'Last Trade Price' },
    { field: 'high', headerName: 'HIGH', type: "number", },
    { field: 'low', headerName: 'LOW', type: "number", },
    { field: 'close_price', headerName: 'CLOSEP', type: "number", description: 'Close Price', },
    { field: 'ycp', headerName: 'YCP', type: "number", description: `Yesterday's  Close Price` },
    { field: 'change', headerName: 'CHANGE', type: "number", },
    { field: 'trade', headerName: 'TRADE', type: "number", },
    { field: 'value_mn', headerName: 'VALUE (mn)', type: "number", },
    { field: 'volume', headerName: 'VOLUME', type: "number", },
    { field: 'data_updated_at_date', headerName: 'Date', type: "date", },
    { field: 'data_updated_at_time', headerName: 'Time' }
  ]

  return (
    <Layout>
      <LayoutCard title="Latest Share Price">
        <DataGridTable title="lsp" columns={ columns } url="latest-share-price" defaultData={ today } />
      </LayoutCard>
    </Layout>
  )
}
