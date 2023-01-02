import { styled } from "@mui/material/styles"
import Box from '@mui/material/Box'
import DataGridColumn from "../components/DataGridColumn"

const DataGridColumns = ({ columns, columnVisibilityModel, setColumnVisibilityModel }) => {

    const Gird = styled(Box)(({ theme }) => ({
        display: "grid",
        gridTemplateColumns: "repeat(2, 1fr)",
        gap: 10,
        marginBottom: 30
    }))

    const lsp = columns.filter(column => column.table === 'lsp')
    const pe = columns.filter(column => column.table === 'pe')

    return (
        <Gird>
            <DataGridColumn title="Latest Share Price" columns={ lsp } columnVisibilityModel={ columnVisibilityModel } setColumnVisibilityModel={ setColumnVisibilityModel}  />
            <DataGridColumn title="Price Earning" columns={ pe } columnVisibilityModel={ columnVisibilityModel } setColumnVisibilityModel={ setColumnVisibilityModel } />
        </Gird>
    )
}

export default DataGridColumns
