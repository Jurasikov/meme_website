
export default function AddPost() {
  function handleSubmit(event) {
    event.preventDefault()
    const formData = new FormData(event.target)
    const options = {
      method: 'POST',
      credentials: 'include',
      body: formData
    }
    fetch(`${process.env.REACT_APP_API}/posts`, options)
    .then((response) => {
      if(response.ok) {
          window.location.reload()
      }
      else throw new Error(`${response.status} ${response.statusText}`)
  })
    .catch(err => console.log(err))
  }

  return (
    <div className="addPost">
      <h2>Dodaj post</h2>
      <form onSubmit={handleSubmit}>
        <input type="text" name="title" placeholder="Tytuł"/>
        <br/>
        <input 
          type="file"
          accept=".jpg, .png, .gif, .mp4, .webm"
          name="media"
        />
        <br/>
        <button type="submit">Dodaj</button>
      </form>
    </div>
  )
}