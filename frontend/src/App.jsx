import { useMemo, useReducer } from 'react'

import CSVLoader from './components/loader'
import Header from './components/header'
import Grid from './components/grid'
import CSVForm from './components/form/form'

const initalState = { isLoading: false }

const isLoadingReducer = (state, action) => {
	if (action.type === 'SET_IS_LOADING') {
		return { ...state, isLoading: !state.isLoading }
	}
}

/**
 * The main app
 * @returns {JSX.Element}
 * @constructor
 */
function App() {
	const [state, dispatch] = useReducer(isLoadingReducer, initalState)
	const { isLoading } = state
	const contextValue = useMemo(() => ({ state, dispatch }), [state, dispatch])

	return (
		<>
			{isLoading && <CSVLoader size={250} />}
			<Header />
			<Grid>
				<CSVForm context={contextValue} />
				<div className="tc">WEBSOCKET INFO</div>
			</Grid>
		</>
	)
}

export default App
