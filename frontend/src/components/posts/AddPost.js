import { useState } from "react"
import Tags from "./Tags";
import {useNavigate} from 'react-router-dom'

export default function AddPost() {
  const [title, setTitle] = useState("");
  const [file, setFile] = useState();
  const [tags, setTags] = useState([]);
  const navigate = useNavigate();

  function handleSubmit(event) {
    event.preventDefault()
    const formData = new FormData();
    formData.append("title", title);
    formData.append("media", file);
    for(let tag of tags) {
      formData.append("tags[]", tag);
    }
    const options = {
      method: 'POST',
      credentials: 'include',
      body: formData
    }
    fetch(`${process.env.REACT_APP_API}/posts`, options)
    .then((response) => {
      if(response.ok) {
          navigate('/');
      }
      else throw new Error(`${response.status} ${response.statusText}`)
  })
    .catch(err => console.log(err))
  }

  function addTag(event) {
    if(event.key === "Enter") {
      setTags([...tags, event.target.value]);
      event.target.value = "";
    }
  }

  return (
    <div className="addPost">
      <h2>Dodaj post</h2>
      <input
        type="text"
        name="title"
        placeholder="Tytuł"
        value={title}
        onChange={(event) => {setTitle(event.target.value)}}
      />
      <br/>
      <input 
        type="file"
        accept=".jpg, .png, .gif, .mp4, .webm"
        name="media"
        onChange={(event) => {setFile(event.target.files[0])}}
      />
      <br/>
      <h4>Tagi</h4>
      {tags && (<Tags tags={tags}/>)}
      <input type="text" placeholder="dodaj tag" onKeyDown={addTag}/>
      <br/>
      <br/>
      <button type="submit" onClick={handleSubmit}>Dodaj post</button>
    </div>
  )
}