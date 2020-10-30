import { PuffLoader } from 'react-spinners'

/**
 * The Loader component
 * @param color - {String} - The color
 * @param size - {Number} - The size
 * @returns {JSX.Element}
 * @constructor
 */
const CSVLoader = ({ color = process.env.REACT_APP_THEME_COLOR, size = 100 }) => (
	<div className="loader">
		<div className="centered">
			<PuffLoader color={color} size={size} />
		</div>
	</div>
)

export default CSVLoader
