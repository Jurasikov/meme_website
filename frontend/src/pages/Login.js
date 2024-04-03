import {useNavigate} from "react-router-dom";

export default function Login(props) {
    const navigate = useNavigate()

    function handleSubmit(event) {
        event.preventDefault()
        const formData = new FormData(event.target)
        const formJson = Object.fromEntries(formData.entries())
        const options = {
            method: 'POST',
            credentials: 'include',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formJson)
        }
        fetch(`${process.env.REACT_APP_API}/login`, options)
        .then((response) => {
            if(response.ok) {
                props.setUsername("loguj")
                navigate("/", {replace: true})
            }
            else throw new Error(`${response.status} ${response.statusText}`)
        })
        .catch(err => console.log(err))
    }

    return (
        <form onSubmit={handleSubmit}>
            <input
                type="text"
                name="username"
                placeholder="nazwa uzytkownika"
            />
            <input
                type="password"
                name="password"
                placeholder="hasło"
            />
            <button type='submit'>Zaloguj</button>
        </form>
    )
}