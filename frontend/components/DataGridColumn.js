import { styled } from "@mui/material/styles"
import Box from '@mui/material/Box'
import FormControlLabel from '@mui/material/FormControlLabel'
import Switch from '@mui/material/Switch'
import Typography from '@mui/material/Typography'
import { useContext } from "react"
import { GlobalContext } from "./contexts/GlobalContext"

const DataGridColumn = ({ title, columns }) => {
    const [state, setState] = useContext(GlobalContext)
    const columnVisibilityModel = state.columnVisibilityModel
    const setColumnVisibilityModel = (columnVisibilityModel) => {
        setState(prevState => ({ ...prevState, columnVisibilityModel }))
    }

    const GirdColumn = styled(Box)(({ theme }) => ({
        position: "relative",
        border: "1px solid #ccc",
        borderRadius: 8,
        padding: 20,
    }))

    const GirdColumnHeading = styled(Typography)(({ theme }) => ({
        fontWeight: "bold",
        position: "absolute",
        top: 0,
        transform: "translateY(-50%)",
        backgroundColor: "#fff",
        padding: "0 10px",
    }))

    const Columns = styled(Box)(({ theme }) => ({
        display: "grid",
        gridTemplateColumns: "repeat(2, 1fr)",
        gap: 10,
    }))

    const Column = styled(Box)(({ theme }) => ({
        border: "1px solid #ccc",
        padding: 10,
        borderRadius: 8,
    }))

    const handleChange = (columnField) => {
        setColumnVisibilityModel({
            ...columnVisibilityModel,
            [columnField]: columnVisibilityModel[columnField] === undefined ? false : !columnVisibilityModel[columnField]
        })
    }

    return (
        <GirdColumn>
            <GirdColumnHeading variant="body1" component="h4" sx={ {} } gutterBottom>
                { title }
            </GirdColumnHeading>
            <Columns>
                { columns.map(column => (
                    <Column key={ column.field }>
                        <FormControlLabel control={ <Switch checked={ columnVisibilityModel[column.field] === undefined ? true : columnVisibilityModel[column.field] } onChange={ () => handleChange(column.field) } /> } label={ column.headerName } />
                    </Column>
                )) }
            </Columns>
        </GirdColumn>
    )
}

export default DataGridColumn
