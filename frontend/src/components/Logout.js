import {useNavigate} from 'react-router-dom'
import { useEffect } from 'react'

export default function Logout(props) {
  const navigate = useNavigate()
  useEffect(() => {
    fetch(`${process.env.REACT_APP_API}/logout`, {method: 'POST', credentials: 'include'})
      .then(() => {
        props.setUsername("")
        navigate("/", {replace: true})
      })
      .catch(err => console.log(err))
  })

  return (
    <></>
  )
}