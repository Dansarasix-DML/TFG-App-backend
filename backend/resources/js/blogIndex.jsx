import ReactDOM from 'react-dom/client';		
import Footer from './components/Reusable/Footer';
import Header from './components/Reusable/Header';
import Posts from './components/Posts';
import '../css/app.css'
import './components/Reusable/header.css';
import './components/Reusable/footer.css';


ReactDOM.createRoot(document.getElementById('blogIndex')).render(		
    <>
        <Header />
        <Posts blogSlug={document.getElementsByName('blogSlug')[0].value} />
        {/* <Test /> */}
        <Footer />
    </>		
);