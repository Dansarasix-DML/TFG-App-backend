import ReactDOM from 'react-dom/client';	
import Header from './components/Reusable/Header';
import React, {useState, useEffect} from 'react';
import Axios from 'axios';
import '../css/login.css'	

const app = document.getElementById('app');
const errors = JSON.parse(app.dataset.errors);

function Login() {
    const [csrf, setCsrf] = useState("");

    useEffect(() => {
        async function fetchUser() {
            try {
                const response = await Axios.post('/csrf');
                setCsrf(response.data);
            } catch (error) {
                console.error(error);
            }
        }
        fetchUser();
    }, []);

    return (
        <div className='login-div'>
            <p>Inicia Sesión</p>
            <hr />
            {console.log(errors)}
            <form action="/login" method="post" className='login-form'>
                <input type="text" name='email' placeholder='Email' className={'login-form ' + errors.email ? "error" : ""}/>
                <input type="password" name='password' placeholder='Password'/>
                <input type="hidden" name="_token" value={csrf.csrf_token} />
                <button type="submit">Login</button>
            </form>
            <p className='form-error'>TODO: {Object.values(errors)}</p>
        </div>	
    )
    
}


ReactDOM.createRoot(app).render(	
    <>
        <Header />
        <Login />	
    </>
);