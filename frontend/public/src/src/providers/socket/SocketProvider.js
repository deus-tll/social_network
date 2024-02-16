import { useEffect } from 'react';
import { io } from 'socket.io-client';
import {useSelector} from "react-redux";
import {selectCurrentToken} from "../../services/auth/authSliceService";
import {handleAvatarsStored, handleConnect, handleDisconnect} from "./socketHandlers";

const SOCKET_SERVER_URL = process.env.REACT_APP_SOCKET_SERVER_URL;

const SocketProvider = ({ children }) => {
  const token = useSelector(selectCurrentToken);

  useEffect(() => {
    const socket = io(SOCKET_SERVER_URL, {
      auth: {
        token: token
      }
    });

    socket.on('connect', handleConnect);
    socket.on('disconnect', handleDisconnect);
    socket.on('avatars.stored', handleAvatarsStored);

    return () => {
      socket.off('connect', handleConnect);
      socket.off('disconnect', handleDisconnect);
      socket.off('avatars.stored', handleAvatarsStored);

      socket.disconnect();
    };
  }, [token]);

  return <>{children}</>;
};

export default SocketProvider;