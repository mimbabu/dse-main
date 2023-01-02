import { useContext, useEffect, useState } from "react"
import Layout from "../components/Layout"
import LayoutCard from "../components/LayoutCard"
import moment from "moment"
import Chart from "../components/Chart"
import Box from '@mui/material/Box'
import Autocomplete from '@mui/material/Autocomplete'
import TextField from '@mui/material/TextField'

import { GlobalContext } from "../components/contexts/GlobalContext"
import { fetchGetData } from '../src/helper'

export default function DayEndSummaryCharts() {
  const [state, setState] = useContext(GlobalContext)
  const [company, setCompany] = useState('')
  const [series, setSeries] = useState([])

  const [options, setOptions] = useState({
    chart: {
      id: 'chart',
      width: '100%',
      height: 'auto',
      type: 'area',
      toolbar: {
        show: true
      },
      zoom: {
        enabled: true,
        type: 'x',
        resetIcon: {
          offsetX: -10,
          offsetY: 0,
          fillColor: '#fff',
          strokeColor: '#37474F'
        },
        selection: {
          background: '#90CAF9',
          border: '#0D47A1'
        }
      }
    },
    colors: ['#7e57c2', '#00bcd4', '#ff9800', '#9c27b0', '#673ab7', '#2196f3', '#009688', '#ff5722', '#795548', '#607d8b'],
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth'
    },
    tooltip: {
      x: {
        format: 'dd/MM/yy HH:mm'
      }
    }
  })

  const handleCountryChange = async (event, newInputValue) => {
    setCompany(newInputValue)
    // if its a valid company, fetch the data
    if (state.companies.map(company => company.code).includes(newInputValue)) {
      setState(prevState => ({ ...prevState, loading: true }))
      const selectedCompany = state.companies.find(company => company.code === newInputValue)
      const des = await fetchGetData(`company/${selectedCompany.id}/day-end-summary`)
      setState(prevState => ({
        ...prevState,
        loading: false,
        chart: {
          ...prevState.chart,
          des: des ? des : []
        }
      }))
    }
  }

  useEffect(() => {
    setSeries([
      {
        name: 'VALUE (mn)',
        data: state.chart.des.map(des => des.value_mn.toFixed(2))
      }
    ])
    setOptions({
      ...options,
      xaxis: {
        categories: state.chart.des.map(des => des.data_updated_at_date)
      },
    })
  }, [state.chart.des])



  useEffect(() => {
    const getData = async () => {
      setState(prevState => ({ ...prevState, loading: true }))
      const companies = await fetchGetData(`company`)
      setState(prevState => ({
        ...prevState,
        loading: false,
        companies: companies ? companies.data.companies : []
      }))
    }
    getData()
  }, [])


  return (
    <Layout>
      <LayoutCard title="Day End Summary">
        <Box sx={ { mx: 'auto' } }>
          <Autocomplete
            disablePortal
            id="company"
            options={ state.companies }
            getOptionLabel={ (option) => option.code }
            sx={ { width: 300 } }
            inputValue={ company }
            onInputChange={ handleCountryChange }
            renderInput={ (params) => <TextField { ...params } label="Company" /> }
            isOptionEqualToValue={ (option, value) => option.code === value.code }
          />
          <Chart options={ options } series={ series } />
        </Box>
      </LayoutCard>
    </Layout>
  )
}
