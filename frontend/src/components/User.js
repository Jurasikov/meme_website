import {NavLink} from "react-router-dom"

export default function User(props) {
  return (
    <div className="user">
      {props.username ? (
      <>
        <p className="username">Użytkownik {props.username}</p>
        <NavLink to="/logout">Wyloguj</NavLink>
      </>
      ) : (
      <>
        <NavLink to="/rejestracja">Zarejestruj</NavLink>
        <NavLink to="/login" reloadDocument>Zaloguj</NavLink>
      </>)}
    </div>
  )
}