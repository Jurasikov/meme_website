export default function Comment(props) {

  return (
    <>
      <div className={props.commentType}>
        <div className="commentBar">
          <p className="author">{props.author}</p>
        </div>
        <p className="commentContent">{props.content}</p>
      </div>
      {props.subcomments ? props.subcomments.map((comment, i) => <Comment {...props.subcomments[i]} key={i} commentType="subcomment"/>) : <></>}
    </>
  )
}