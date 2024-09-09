import { useState } from "react";
import { NavLink } from "react-router-dom";

export default function Tags(props) {
  return (
    <div className="tags">
      {props.tags && props.tags.map((tag, i) => {return (<NavLink to={`/tag/${tag}`} key={i}>{"#" + tag}</NavLink>)})}
    </div>
  )
}