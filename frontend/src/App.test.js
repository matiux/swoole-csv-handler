import { render, screen } from '@testing-library/react'
import App from './App'

describe('App Test Suite', () => {
	it('should render the works word', () => {
		render(<App />)
		const worksWord = screen.getByText(/Works/)
		expect(worksWord).toBeInTheDocument()
	})
})
