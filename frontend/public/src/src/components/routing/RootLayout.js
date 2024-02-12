import {Outlet} from "react-router-dom";
import {setCredentials} from "../../services/auth/authSliceService";
import {useDispatch} from "react-redux";

const RootLayout = () => {
  const appName = process.env.REACT_APP_NAME;

  const dispatch = useDispatch()

  const storedToken = localStorage.getItem('accessToken');
  const storedUser = localStorage.getItem('user');

  if (storedUser && storedToken) {
    dispatch(setCredentials({ user: storedUser, accessToken: storedToken }));
  }

  return (
    <div className="container mt-4">
      <main style={{minHeight: 'calc(100vh - 90px)'}}>
        <Outlet/>
      </main>

      <footer className="mb-5">
        <p>&copy; {new Date().getFullYear()} {appName}</p>
      </footer>
    </div>
  );
};

export default RootLayout;