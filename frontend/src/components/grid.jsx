/**
 * The base rid
 * @param children - {JSX.Element[]} - The childrens to display
 * @returns {JSX.Element}
 * @constructor
 */
const Grid = ({ children }) => (
	<div className="container main">
		<div className="grid-2-cols">{children}</div>
	</div>
)

export default Grid
