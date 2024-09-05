import { useState } from "react";

export default function Tags(props) {
  return (
    <div className="tags">
      {props.tags && props.tags.map((tag, i) => {return (<p key={i}>{"#" + tag}</p>)})}
    </div>
  )
}