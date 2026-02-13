import ReactDOM from 'react-dom/client';	
import '../css/registro.css'	

const app = document.getElementById('app');
const csrf = app.dataset.csrfToken;
const errors = JSON.parse(app.dataset.errors);


ReactDOM.createRoot(app).render(		
    <form action="" method="post" className='register-form'>
        <input type="text" name='name' placeholder='Nombre'/>
        <input type="text" name='username' placeholder='Username'/>
        <input type="text" name='email' placeholder='Email'/>
        <input type="password" name='password' placeholder='Password'/>
        <input type="hidden" name="_token" value={csrf} />
        <input type="submit" value="ENVIAR" />
        <h1>TODO: {Object.values(errors)}</h1>
    </form>
);