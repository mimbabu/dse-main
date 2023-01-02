import React from 'react'
import { GridToolbarContainer, GridToolbarColumnsButton, GridToolbarFilterButton, GridToolbarDensitySelector, GridToolbarExport, GridToolbarProps } from '@mui/x-data-grid'

const DataGridToolbar = () => {
  return (
    <GridToolbarContainer>
      <GridToolbarColumnsButton />
      <GridToolbarFilterButton />
      <GridToolbarDensitySelector />
      <GridToolbarExport />
      {/* <div>DataGridToolbar</div> */ }
    </GridToolbarContainer>
  )
}

export default DataGridToolbar
