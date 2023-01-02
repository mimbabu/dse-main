import { useCallback, useContext, useEffect, useState } from 'react'
import { GlobalContext } from '../components/contexts/GlobalContext'
import Box from '@mui/material/Box'
import MobileDatePicker from '@mui/lab/MobileDatePicker'
import TextField from '@mui/material/TextField'
import { DataGrid, GridToolbar } from '@mui/x-data-grid'
import moment from "moment"
import { isWeekend, isFriday, fetchGetData, isEmpty } from '../src/helper'
import Search from './Search'

const DataGridTable = ({ title, columns, url, defaultData }) => {
  const [state, setState] = useContext(GlobalContext)
  const [page, setPage] = useState(1)
  const [pageSize, setPageSize] = useState(10)
  const today = moment()
  const validDate = isWeekend(defaultData) ? (isFriday(defaultData) ? defaultData.subtract(1, 'days') : defaultData.subtract(2, 'days')) : defaultData
  const [fromDate, setFromDate] = useState(validDate)
  const [toDate, setToDate] = useState(validDate)
  const [query, setQuery] = useState('')
  const [sortModel, setSortModel] = useState([])
  const [filterModel, setFilterModel] = useState([])
  const columnVisibilityModel = state.columnVisibilityModel
  const setColumnVisibilityModel = (columnVisibilityModel) => {
    setState(prevState => ({ ...prevState, columnVisibilityModel }))
  }

  const onFilterModelChange = useCallback((filterModel) => {
    setFilterModel(filterModel.items)
  }, [])

  useEffect(() => {
    const getData = async () => {
      setState(prevState => ({ ...prevState, loading: true }))
      const from_date = fromDate.format('YYYY-MM-DD')
      const to_date = toDate.format('YYYY-MM-DD')
      let queryUrl = `${url}?page=${page}&_limit=${pageSize}&from_date=${from_date}&to_date=${to_date}`
      if (query) {
        queryUrl += `&q=${query}`
      }
      sortModel.map(item => {
        queryUrl += `&_sort=${item.field}&_order=${item.sort}`
      })
      if (filterModel.length > 0) {
        filterModel.map(item => {
          if (((
            item.operatorValue === "contains" ||
            item.operatorValue === "equals" ||
            item.operatorValue === "startsWith" ||
            item.operatorValue === "endsWith" ||
            item.operatorValue === "=" ||
            item.operatorValue === "!=" ||
            item.operatorValue === ">" ||
            item.operatorValue === "<" ||
            item.operatorValue === "<=" ||
            item.operatorValue === ">="
          ) &&
            item.value === undefined
          )) {
            return
          }
          queryUrl += `&_filter=${item.columnField}&_filterOperatorValue=${item.operatorValue}&_filterValue=${item.value}`
        })
      }
      const data = await fetchGetData(queryUrl)
      if (data) {
        setState(prevState => ({ ...prevState, [title]: data, loading: false }))
      }
    }
    getData()
  }, [fromDate, toDate, pageSize, page, sortModel, filterModel, query])

  return (
    <Box>
      <Box sx={ { mb: 2.5, display: "flex", gap: 2.5 } }>
        <MobileDatePicker
          label="From Date"
          inputFormat="YYYY-MM-DD"
          value={ fromDate }
          onChange={ newDate => setFromDate(newDate) }
          renderInput={ (params) => <TextField { ...params } /> }
          showDaysOutsideCurrentMonth
          maxDate={ moment() }
          shouldDisableDate={ isWeekend }
        />
        <MobileDatePicker
          label="To Date"
          inputFormat="YYYY-MM-DD"
          value={ toDate }
          onChange={ newDate => setToDate(newDate) }
          renderInput={ (params) => <TextField { ...params } /> }
          showDaysOutsideCurrentMonth
          maxDate={ moment() }
          shouldDisableDate={ isWeekend }
        />
        <Search query={ query } setQuery={ setQuery } />
      </Box>
      <Box sx={ { width: '100%', pb: 2.5 } }>
        <DataGrid
          rows={ state[title].data ? state[title].data : [] }
          rowCount={ state[title].total ? state[title].total : 0 }
          componentsProps={ { toolbar: { csvOptions: { fileName: `${url}-${today.format('YYYY-MM-DD')}` } } } }
          loading={ state.loading }
          columns={ columns }
          columnVisibilityModel={ columnVisibilityModel }
          onColumnVisibilityModelChange={ newModel => setColumnVisibilityModel(newModel) }
          autoHeight
          components={ { Toolbar: GridToolbar } }
          pageSize={ pageSize }
          pagination
          rowsPerPageOptions={ state.rowsPerPageOptions }
          paginationMode="server"
          onPageChange={ page => setPage(++page) }
          onPageSizeChange={ (newPage) => setPageSize(newPage) }
          sortingMode="server"
          sortModel={ sortModel }
          onSortModelChange={ newSortModel => setSortModel(newSortModel) }
          filterMode="server"
          onFilterModelChange={ onFilterModelChange }
        />
      </Box>
    </Box>
  )
}

DataGridTable.defaultProps = {
  defaultData: moment()
}

export default DataGridTable
