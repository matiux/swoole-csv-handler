import wretch from 'wretch'

/**
 * Function to upload a file
 * @param file - {File} - The file to upload
 * @returns {Promise}
 */
export default async function uploadFile(file) {
	try {
		const excelBody = {
			file_to_upload: file,
		}

		return await wretch(`${process.env.REACT_APP_ENDPOINT}/upload`)
			.formData(excelBody)
			.post()
			.res((res) => res)
	} catch (e) {
		console.error(e)
	}
}
