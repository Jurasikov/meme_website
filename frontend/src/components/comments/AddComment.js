export default function AddComment(props) {
  function handleSubmit(event){
    event.preventDefault();
    const body = {
      replyTo: props.reply,
      content: event.target.comment.value
    };
    const options = {
      method: 'POST',
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body)
    };
    fetch(`${process.env.REACT_APP_API}/posts/${props.postId}/comments`, options)
    .then((response) => {
      if(response.ok) {
          props.setRefresh(true);
          event.target.comment.value = '';
      }
      else throw new Error(`${response.status} ${response.statusText}`)
      })
    .catch(err => console.log(err))
  }
  return (
    <div className="AddComment">
      <form onSubmit={handleSubmit}>
        <textarea name="comment"/>
        <button type="submit">Dodaj</button>
      </form>
    </div>
  )
}