/**
 * A file input
 * @param register - {function} - The register function for the hook form
 * @returns {JSX.Element}
 * @constructor
 */
const InputFile = ({ register }) => (
	<input accept=".xlsx, .xls, .csv" className="inputs input-file" name="csvInput" ref={register} type="file" />
)

export default InputFile
