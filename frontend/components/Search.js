import Paper from '@mui/material/Paper'
import InputBase from '@mui/material/InputBase'
import IconButton from '@mui/material/IconButton'
import SearchIcon from '@mui/icons-material/Search'

const Search = ({ query, setQuery }) => {
  return (
    <Paper component="form" elevation={ 0 } sx={ {
      p: '2px 4px',
      display: 'flex',
      alignItems: 'center',
      width: 320,
      border: '1px solid rgba(0, 0, 0, 0.23)'
    } }>
      <IconButton sx={ { p: '10px' } } type="button" aria-label="Search">
        <SearchIcon />
      </IconButton>
      <InputBase
        sx={ { ml: 1, flex: 1 } }
        placeholder="Search Data"
        inputProps={ { 'aria-label': 'search' } }
        value={ query }
        onChange={ e => setQuery(e.target.value) }
      />
    </Paper>
  )
}

export default Search
