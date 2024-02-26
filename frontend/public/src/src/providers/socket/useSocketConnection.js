import {useEffect, useState} from "react";
import { io } from 'socket.io-client';
import {handleConnect, handleDisconnect, handleMyNameIs, handlePing} from "./socketHandlers";
import {useSelector} from "react-redux";
import {selectCurrentToken} from "../../services/auth/authSliceService";

const SOCKET_SERVER_URL = process.env.REACT_APP_SOCKET_SERVER_URL;

const useSocketConnection = () => {
  const token = useSelector(selectCurrentToken);
  const [socket, setSocket] = useState(null);

  // useEffect(() => {
  //   if (!socket && token) {
  //     const newSocket = io(SOCKET_SERVER_URL, {
  //       auth: {
  //         token: token
  //       }
  //     });
  //
  //     newSocket.connect();
  //
  //     setSocket(newSocket);
  //   }
  //
  //   return () => {
  //     socket?.disconnect();
  //   };
  // }, [token]);
  //
  // useEffect(() => {
  //   socket?.on('connect', handleConnect);
  //   socket?.on('disconnect', handleDisconnect);
  //   socket?.on('socket.myNameIs', handleMyNameIs);
  //   socket?.on('ping', handlePing);
  //
  //   return () => {
  //     socket?.off('connect', handleConnect);
  //     socket?.off('disconnect', handleDisconnect);
  //     socket?.off('socket.myNameIs', handleMyNameIs);
  //     socket?.off('ping', handlePing);
  //   };
  // });

  const connect = () => {
    if (!socket && token) {
      const newSocket = io(SOCKET_SERVER_URL, {
        auth: {
          token: token
        }
      });

      newSocket.connect();

      setSocket(newSocket);
    }

    socket?.on('connect', handleConnect);
    socket?.on('disconnect', handleDisconnect);
    socket?.on('socket.myNameIs', handleMyNameIs);
    socket?.on('ping', handlePing);
  };

  const disconnect = () => {
    socket?.disconnect();

    socket?.off('connect', handleConnect);
    socket?.off('disconnect', handleDisconnect);
    socket?.off('socket.myNameIs', handleMyNameIs);
    socket?.off('ping', handlePing);
  };

  const on = (eventName, callBack) => {
    console.log('useSocketConnection on - ', {eventName, callBack});
    console.log('useSocketConnection socket on - ', socket);

    socket?.on(eventName, callBack);
  };

  const off = (eventName, callBack) => {
    console.log('useSocketConnection off - ', {eventName, callBack});
    console.log('useSocketConnection socket off - ', socket);

    socket?.off(eventName, callBack);
  };

  return { connect, disconnect, on, off };
};

export default useSocketConnection;