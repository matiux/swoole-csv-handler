import { useState } from 'react'
import { useForm } from 'react-hook-form'

import InputFile from './inputFile'
import Button from '../button'

import UploadFile from '../../api/uploadFile'

/**
 * The form component
 * @returns {JSX.Element}
 * @constructor
 */
const CSVForm = () => {
	const [responseStatus, setResponseStatus] = useState('')
	const { formState, handleSubmit, register } = useForm({ mode: 'onChange' })

	const onSubmit = async ({ csvInput }) => {
		const [file] = csvInput

		const { statusText } = await UploadFile(file)
		setResponseStatus(statusText)
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
