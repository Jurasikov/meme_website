import { useEffect } from "react";
import VoteBar from "./VoteBar";
import { NavLink } from "react-router-dom";

export default function PostComponent(props) {

  return (
    <article>
      <div className="titleBar">
        <p className="author">{props.author}</p>
        <h3 className="title">{props.title}</h3>
      </div>
      <div className="content">
        {/\.jpg$|\.png$|\.gif$/.test(props.file_name) &&
        <img
          src={`${process.env.REACT_APP_MEDIA_SOURCE}/${props.file_name}`}
          alt='ups'
        />}
        {/\.mp4$|\.webm$/.test(props.file_name) &&
        <video controls>
          <source src={`${process.env.REACT_APP_MEDIA_SOURCE}/${props.file_name}`}
          alt='ups'/>
        </video>}
        <div className="bottomBar">
          <NavLink to={`/post/${props.id}`}>Komentarze</NavLink>
          <VoteBar ratio={props.ratio} vote={props.vote ? props.vote : null} id={props.id} username={props.username}/>
        </div>
      </div>
      
    </article>
  )
}