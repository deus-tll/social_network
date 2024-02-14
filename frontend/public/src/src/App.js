import { Routes, Route } from 'react-router-dom';
import RootLayout from "./components/routing/RootLayout";
import Login from "./pages/auth/Login";
import Home from "./pages/Home";
import Welcome from "./pages/Welcome";
import './App.css';
import RequireAuth from "./components/routing/RequireAuth";
import Register from "./pages/auth/Register";


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
