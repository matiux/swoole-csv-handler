/**
 * The button component
 * @param disabled - {Boolean} - If should be disabled or not
 * @param type - {String} - The type of the button
 * @param text - {String} - The text to display
 * @returns {JSX.Element}
 * @constructor
 */
const Button = ({ disabled = false, text, type = 'button' }) => (
	<button className={`csv-btn ${disabled ? 'disabled' : ''}`} disabled={disabled} type={type}>
		{text}
	</button>
)

export default Button
