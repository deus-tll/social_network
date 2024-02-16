import { useLocation, Navigate, Outlet } from "react-router-dom";
import { useSelector } from "react-redux";
import {selectCurrentToken} from "../../services/auth/authSliceService";
import SocketProvider from "../../providers/socket/SocketProvider";

const RequireAuth = () => {
  const token = useSelector(selectCurrentToken);
  const location = useLocation();

  return (
    token
      ? (
        <SocketProvider>
          <Outlet/>
        </SocketProvider>)
      : <Navigate to="/login" state={{from: location}} replace />
  )
};

export default RequireAuth;