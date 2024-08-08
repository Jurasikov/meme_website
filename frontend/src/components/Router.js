import { Route, Routes} from "react-router-dom";
import Home from "../pages/Home";
import Login from "../pages/Login";
import Register from "../pages/Register";
import Logout from "./Logout";
import Post from "../pages/Post";

export default function Router(props) {
  return(
    <Routes>
      <Route path="/:page?" element={<Home username={props.username} setUsername={props.setUsername}/>}/>
      <Route path="/login" element={<Login username={props.username} setUsername={props.setUsername}/>}/>
      <Route path="/rejestracja" element={<Register username={props.username} setUsername={props.setUsername}/>}/>
      <Route path="/logout" element={<Logout username={props.username} setUsername={props.setUsername}/>}/>
      <Route path="/post/:number" element={<Post username={props.username}/>}/>
    </Routes>
  )
}