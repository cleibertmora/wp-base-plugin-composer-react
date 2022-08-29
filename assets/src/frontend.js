// import React from 'react'
// import ReactDOM from 'react-dom'
import './css/frontend.scss'
import { Tab } from 'bootstrap';

const triggerTabList = document.querySelectorAll('#myTab button')
triggerTabList.forEach(triggerEl => {
  const tabTrigger = new Tab(triggerEl)

  triggerEl.addEventListener('click', event => {
    event.preventDefault()
    tabTrigger.show()
  })
})

// const divAutoagendamiento = document.querySelector("#vic-entreno-app-ficha-cliente")
// divAutoagendamiento.classList.add("vic-entreno-app-styles")

// ReactDOM.render(<Autoagendamiento />, divAutoagendamiento)

// function Autoagendamiento() {
//     return (
//         <h1 className="text-center">Hello Moto</h1>
//     )
// }