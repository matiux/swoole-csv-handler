import { useState } from 'react'
import { useForm } from 'react-hook-form'

import InputFile from './inputFile'
import Button from '../button'

import UploadFile from '../../api/uploadFile'

/**
 * The form component
 * @param context - {Object} - The memoized context
 * @returns {JSX.Element}
 * @constructor
 */
const CSVForm = ({ context }) => {
	const [responseStatus, setResponseStatus] = useState('')
	const { formState, handleSubmit, register } = useForm({ mode: 'onChange' })
	const { dispatch } = context

	const onSubmit = async ({ csvInput }) => {
		const [file] = csvInput
		setResponseStatus('')

		dispatch({ type: 'SET_IS_LOADING', payload: true })

		const { msg } = await UploadFile(file)
		setResponseStatus(msg)

		dispatch({ type: 'SET_IS_LOADING', payload: false })
	}

	return (
		<form className="form" onSubmit={handleSubmit(onSubmit)}>
			<InputFile
				register={register({
					validate: (fileList) => fileList.length >= 1,
				})}
			/>
			<Button disabled={!formState.isValid} type="submit" text="Carica!" />
			{responseStatus.length > 1 && <div className="mt-4 w-full">{responseStatus}</div>}
		</form>
	)
}

export default CSVForm
