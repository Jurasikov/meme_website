export default function Post(props) {

  return (
    <article>
      <div className="titleBar">
        <p className="author">{props.post['author']}</p>
        <h3 className="title">{props.post['title']}</h3>
      </div>
      <div className="content">
        {/\.jpg$|\.png$|\.gif$/.test(props.post['file_name']) &&
        <img
          src={`${process.env.REACT_APP_MEDIA_SOURCE}/${props.post['file_name']}`}
          alt='ups'
        />}
        {/\.mp4$|\.webm$/.test(props.post['file_name']) &&
        <video controls>
          <source src={`${process.env.REACT_APP_MEDIA_SOURCE}/${props.post['file_name']}`}
          alt='ups'/>
        </video>}
      </div>
      
    </article>
  )
}