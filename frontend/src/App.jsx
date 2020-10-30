import Header from './components/header'
import Grid from './components/grid'
import CSVForm from './components/form/form'

/**
 * The main app
 * @returns {JSX.Element}
 * @constructor
 */
function App() {
	return (
		<>
			<Header />
			<Grid>
				<div className="tc">
					<CSVForm />
				</div>
				<div className="tc">WEBSOCKET INFO</div>
			</Grid>
		</>
	)
}

export default App
