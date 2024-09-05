import { useState } from "react"
import AddComment from "./AddComment";

export default function Comment(props) {
  const [reply, showReply] = useState(false);
  return (
    <>
      <div className={props.commentType}>
        <div className="commentBar">
          <p className="author">{props.author}</p>
        </div>
        <p className="commentContent">{props.content}</p>
        <p onClick={() => {showReply(true)}}>Odpowiedz</p>
        {reply  && <AddComment postId={props.postId} reply={props.id} setRefresh={props.setRefresh}/>}
      </div>
      {props.subcomments ? props.subcomments.map((comment, i) => <Comment {...props.subcomments[i]} key={i} commentType="subcomment" postId={props.postId} setRefresh={props.setRefresh}/>) : <></>}
    </>
  )
}