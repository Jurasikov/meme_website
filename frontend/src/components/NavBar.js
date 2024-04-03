import {NavLink} from "react-router-dom";
import User from "./User";

export default function NavBar(props) {
  return (
    <nav className="navBar">
      <NavLink to="/">Home</NavLink>
      <User username={props.username} setUsername={props.setUsername}/>
    </nav>
  )
}