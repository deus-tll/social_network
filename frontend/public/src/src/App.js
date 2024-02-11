import { Routes, Route } from 'react-router-dom';
import RootLayout from "./components/routing/RootLayout";
import Login from "./components/auth/Login";
import Home from "./components/Home";
import Welcome from "./components/Welcome";
import './App.css';


function App() {
  return (
    <Routes>
      <Route path="/" element={<RootLayout/>}>
        {/* public routes */}
        <Route index element={<Home/>}/>
        <Route path="login" element={<Login />} />
        <Route path="welcome" element={<Welcome />} />
      </Route>
    </Routes>
  );
}

export default App;
