import {createContext, useContext, useEffect} from 'react';
import useSocketConnection from "./useSocketConnection";

const SocketContext = createContext();

export const useSocket = () => useContext(SocketContext);

export const SocketProvider = ({ children }) => {
  const socketConnection = useSocketConnection();

  useEffect(() => {
    socketConnection.connect();

    return () => {
      socketConnection.disconnect();
    };
  }, [socketConnection]);

  return (
    <SocketContext.Provider value={{ socketConnection }}>
      {children}
    </SocketContext.Provider>
  );
};