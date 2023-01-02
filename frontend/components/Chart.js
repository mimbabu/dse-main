import dynamic from 'next/dynamic'

const ApexCharts = dynamic(() => import('react-apexcharts'), { ssr: false })

const Chart = ({ type, width, height, options, series }) => {
    return (
        <ApexCharts type={ type } width={ width } height={ height } options={ options } series={ series } />
    )
}

Chart.defaultProps = {
    type: 'area',
    width: '100%',
    height: 'auto',
    options: {},
    series: []
}

export default Chart
