import { Route, Routes} from "react-router-dom";
import Home from "../pages/Home";
import Login from "../pages/Login";
import Register from "../pages/Register";
import Logout from "./Logout";

export default function Router(props) {
  return(
    <Routes>
      <Route path="/" element={<Home username={props.username} setUsername={props.setUsername}/>}/>
      <Route path="/login" element={<Login username={props.username} setUsername={props.setUsername}/>}/>
      <Route path="/rejestracja" element={<Register username={props.username} setUsername={props.setUsername}/>}/>
      <Route path="/logout" element={<Logout username={props.username} setUsername={props.setUsername}/>}/>
    </Routes>
  )
}