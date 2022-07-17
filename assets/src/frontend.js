import React from 'react'
import ReactDOM from 'react-dom'
import './css/frontend.scss'

const divAutoagendamiento = document.querySelector("#clinic-manager-wp-autoagendamiento")
divAutoagendamiento.classList.add("clinic-manager-wp")

ReactDOM.render(<Autoagendamiento />, divAutoagendamiento)

function Autoagendamiento() {
    return (
        <h1 className="text-center">Hello Moto</h1>
    )
}