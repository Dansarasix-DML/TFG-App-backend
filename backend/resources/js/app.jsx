import ReactDOM from 'react-dom/client';		
import Footer from './components/Reusable/Footer';
import Header from './components/Reusable/Header';
import Index from './components/Index';
import '../css/app.css';
import './components/Reusable/header.css';
import './components/Reusable/footer.css';


ReactDOM.createRoot(document.getElementById('app')).render(		
    <>
        <Header />
        {/* <Test /> */}
        <Index />
        <Footer />
    </>		
);