import Layout from "../components/Layout"
import DataGridTable from '../components/DataGridTable'
import LayoutCard from "../components/LayoutCard"
import moment from "moment"

export default function DayEndSummary() {
  const today = moment()
  const yesterday = today.subtract(1, 'days')

  const columns = [
    { field: 'id', headerName: '#', type: "number", width: 70 },
    { field: 'company', headerName: 'Code', valueFormatter: ({ value }) => value.code, },
    { field: 'ltp', headerName: 'LTP', type: "number", description: 'Last Trade Price' },
    { field: 'high', headerName: 'HIGH', type: "number", },
    { field: 'low', headerName: 'LOW', type: "number", },
    { field: 'open_price', headerName: 'OPENP', type: "number", description: 'Open Price', },
    { field: 'close_price', headerName: 'CLOSEP', type: "number", description: 'Close Price', },
    { field: 'ycp', headerName: 'YCP', type: "number", description: `Yesterday's  Close Price` },
    { field: 'trade', headerName: 'TRADE', type: "number", },
    { field: 'value_mn', headerName: 'VALUE (mn)', type: "number", },
    { field: 'volume', headerName: 'VOLUME', type: "number", },
    { field: 'data_updated_at_date', headerName: 'Date', type: "date", },
    { field: 'data_updated_at_time', headerName: 'Time' }
  ]

  return (
    <Layout>
      <LayoutCard title="Day End Summary">
        <DataGridTable title="des" columns={ columns } url="day-end-summary" defaultData={ yesterday } />
      </LayoutCard>
    </Layout>
  )
}
