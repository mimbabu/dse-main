import { useState } from "react"
import Layout from "../components/Layout"
import DataGridTable from '../components/DataGridTable'
import LayoutCard from "../components/LayoutCard"
import moment from "moment"
import DataGridColumns from "../components/DataGridColumns"

export default function CustomData() {
  const today = moment()
  const columns = [
    { field: 'id', headerName: '#', type: "number", width: 70, table: 'lsp' },
    { field: 'company', headerName: 'Code', table: 'lsp', valueFormatter: ({ value }) => value.code, },
    { field: 'ltp', headerName: 'LTP', type: "number", description: 'Last Trade Price', table: 'lsp' },
    { field: 'high', headerName: 'HIGH', type: "number", table: 'lsp' },
    { field: 'low', headerName: 'LOW', type: "number", table: 'lsp' },
    { field: 'pe_1', headerName: 'PE1', type: "number", table: 'pe', valueGetter: ({ row }) => row.company.price_earnings[0].pe_1, },
    { field: 'pe_2', headerName: 'PE2', type: "number", table: 'pe', valueGetter: ({ row }) => row.company.price_earnings[0].pe_2, },
    { field: 'pe_3', headerName: 'PE3', type: "number", table: 'pe', valueGetter: ({ row }) => row.company.price_earnings[0].pe_3, },
    { field: 'pe_4', headerName: 'PE4', type: "number", table: 'pe', valueGetter: ({ row }) => row.company.price_earnings[0].pe_4, },
    { field: 'pe_5', headerName: 'PE5', type: "number", table: 'pe', valueGetter: ({ row }) => row.company.price_earnings[0].pe_5, },
    { field: 'pe_6', headerName: 'PE6', type: "number", table: 'pe', valueGetter: ({ row }) => row.company.price_earnings[0].pe_6, },
    { field: 'close_price', headerName: 'CLOSEP', type: "number", description: 'Close Price', table: 'lsp' },
    { field: 'ycp', headerName: 'YCP', type: "number", description: `Yesterday's  Close Price`, table: 'lsp' },
    { field: 'change', headerName: 'CHANGE', type: "number", table: 'lsp' },
    { field: 'trade', headerName: 'TRADE', type: "number", table: 'lsp' },
    { field: 'value_mn', headerName: 'VALUE (mn)', type: "number", table: 'lsp' },
    { field: 'volume', headerName: 'VOLUME', type: "number", table: 'lsp' },
    { field: 'data_updated_at_date', headerName: 'Date', type: "date", table: 'lsp' },
    { field: 'data_updated_at_time', headerName: 'Time', table: 'lsp' },
  ]


  return (
    <Layout>
      <LayoutCard title="Custom Data">
        <DataGridColumns columns={ columns } />
        <DataGridTable title="data" columns={ columns } url="data" defaultData={ today } />
      </LayoutCard>
    </Layout >
  )
}
