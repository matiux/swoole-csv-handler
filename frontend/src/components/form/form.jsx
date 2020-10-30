import { useForm } from 'react-hook-form'

const CSVForm = () => {
	const { handleSubmit, register } = useForm()
	const onSubmit = ({ csvInput }) => console.info(csvInput)

	return (
		<form onSubmit={handleSubmit(onSubmit)}>
			<input accept=".xlsx, .xls, .csv" name="csvInput" ref={register} type="file" />
			<button type="submit">Carica!</button>
		</form>
	)
}

export default CSVForm
