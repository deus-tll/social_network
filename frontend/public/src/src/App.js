import { Routes, Route } from 'react-router-dom';
import RootLayout from "./components/routing/RootLayout";
import Login from "./components/auth/Login";
import Home from "./components/Home";
import Welcome from "./components/Welcome";
import './App.css';
import RequireAuth from "./components/routing/RequireAuth";
import Register from "./components/auth/Register";


function App() {
  return (
    <Routes>
      <Route path="login" element={<Login/>}/>
      <Route path="register" element={<Register/>}/>

      <Route path="/" element={<RootLayout/>}>
        {/* public routes */}
        <Route index element={<Home/>}/>

        {/* protected routes */}
        <Route element={<RequireAuth/>}>
          <Route path="welcome" element={<Welcome/>}/>
        </Route>
      </Route>
    </Routes>
  );
}

export default App;
